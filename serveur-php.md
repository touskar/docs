# Intégration PHP

Ce guide vous explique comment intégrer PayTech dans vos applications PHP en utilisant cURL ou les fonctions HTTP natives.

## Configuration de base

Créez un fichier de configuration pour vos clés PayTech :

```php
<?php
// config.php

define('PAYTECH_API_KEY', 'votre_api_key');
define('PAYTECH_SECRET_KEY', 'votre_secret_key');
define('PAYTECH_ENV', 'test'); // 'test' ou 'prod'
define('PAYTECH_BASE_URL', 'https://paytech.sn/api/payment');
define('PAYTECH_IPN_URL', 'https://votre-site.com/ipn.php');

// URLs pour intégrations mobiles (Flutter, React Native)
define('MOBILE_SUCCESS_URL', 'https://paytech.sn/mobile/success');
define('MOBILE_CANCEL_URL', 'https://paytech.sn/mobile/cancel');

// URLs pour intégrations web
define('WEB_SUCCESS_URL', 'https://votre-site.com/success.php');
define('WEB_CANCEL_URL', 'https://votre-site.com/cancel.php');
```

## Classe PayTech

```php
<?php
// PayTechClient.php

require_once 'config.php';

class PayTechClient
{
    private $apiKey;
    private $secretKey;
    private $env;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = PAYTECH_API_KEY;
        $this->secretKey = PAYTECH_SECRET_KEY;
        $this->env = PAYTECH_ENV;
        $this->baseUrl = PAYTECH_BASE_URL;
    }

    /**
     * Créer une demande de paiement
     */
    public function requestPayment(
        $itemName,
        $itemPrice,
        $refCommand,
        $customField = [],
        $paymentMethod = null,
        $user = null,
        $isMobile = false
    ) {
        $url = $this->baseUrl . '/request-payment';
        
        // Choisir les URLs selon le type d'intégration
        if ($isMobile) {
            $successUrl = MOBILE_SUCCESS_URL;
            $cancelUrl = MOBILE_CANCEL_URL;
        } else {
            $successUrl = WEB_SUCCESS_URL;
            $cancelUrl = WEB_CANCEL_URL;
        }
        
        $payload = [
            'item_name' => $itemName,
            'item_price' => $itemPrice,
            'currency' => 'xof',
            'ref_command' => $refCommand,
            'command_name' => $itemName,
            'env' => $this->env,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'ipn_url' => PAYTECH_IPN_URL,
            'custom_field' => json_encode($customField),
        ];
        
        // Ajouter target_payment si spécifié
        if ($paymentMethod) {
            $payload['target_payment'] = $paymentMethod;
        }
        
        $headers = [
            'API_KEY: ' . $this->apiKey,
            'API_SECRET: ' . $this->secretKey,
            'Content-Type: application/json'
        ];
        
        try {
            $response = $this->makeRequest($url, $payload, $headers);
            
            if ($response['success']) {
                $responseData = $response['data'];
                
                // Ajouter les paramètres de préfillage si utilisateur fourni et mobile
                if ($user && $isMobile) {
                    $queryParams = [
                        'pn' => $user['phone_number'],
                        'nn' => substr($user['phone_number'], 4), // Numéro national
                        'fn' => $user['first_name'] . ' ' . $user['last_name'],
                        'tp' => $paymentMethod,
                        'nac' => 1 // Auto-submit
                    ];
                    
                    $queryString = http_build_query($queryParams);
                    $responseData['redirect_url'] .= '?' . $queryString;
                    $responseData['redirectUrl'] = $responseData['redirect_url'];
                }
                
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }
            
            return $response;
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'errors' => [$e->getMessage()]
            ];
        }
    }
    
    /**
     * Effectuer une requête HTTP avec cURL
     */
    private function makeRequest($url, $data, $headers)
    {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            throw new Exception('Erreur cURL: ' . $error);
        }
        
        $responseData = json_decode($response, true);
        
        if (in_array($httpCode, [200, 201])) {
            return [
                'success' => true,
                'data' => $responseData
            ];
        }
        
        return [
            'success' => false,
            'errors' => [$httpCode],
            'response' => $responseData
        ];
    }
    
    /**
     * Valider une notification IPN
     */
    public function validateIPN($data)
    {
        $receivedApiKeyHash = $data['api_key_sha256'] ?? '';
        $receivedSecretHash = $data['api_secret_sha256'] ?? '';
        
        $expectedApiKeyHash = hash('sha256', $this->apiKey);
        $expectedSecretHash = hash('sha256', $this->secretKey);
        
        return $receivedApiKeyHash === $expectedApiKeyHash && 
               $receivedSecretHash === $expectedSecretHash;
    }
    
    /**
     * Valider une signature HMAC
     */
    public function validateHMAC($data)
    {
        if (!isset($data['hmac_compute'])) {
            return false;
        }
        
        $receivedHmac = $data['hmac_compute'];
        
        // Reconstituer le message pour la vérification HMAC
        $message = $data['amount'] . '|' . $data['id_transaction'] . '|' . $this->apiKey;
        $expectedHmac = hash_hmac('sha256', $message, $this->secretKey);
        
        return hash_equals($expectedHmac, $receivedHmac);
    }
    
    /**
     * Générer une signature HMAC
     */
    public function generateHMAC($message)
    {
        return hash_hmac('sha256', $message, $this->secretKey);
    }
}
```

## Utilisation de base

### Créer un paiement (Web)

```php
<?php
// create_payment.php

require_once 'PayTechClient.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paytech = new PayTechClient();
    
    $itemName = $_POST['item_name'];
    $itemPrice = (int)$_POST['item_price'];
    $refCommand = 'CMD_' . uniqid();
    $customField = [
        'user_id' => $_POST['user_id'] ?? null,
        'order_type' => 'web'
    ];
    $paymentMethod = $_POST['payment_method'] ?? null;
    
    $result = $paytech->requestPayment(
        $itemName,
        $itemPrice,
        $refCommand,
        $customField,
        $paymentMethod,
        null,
        false // Web integration
    );
    
    if ($result['success']) {
        // Sauvegarder en base de données
        saveTransaction($refCommand, $result['data']);
        
        // Rediriger vers PayTech
        header('Location: ' . $result['data']['redirect_url']);
        exit;
    } else {
        echo "Erreur: " . implode(', ', $result['errors']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Créer un paiement</title>
</head>
<body>
    <h1>Formulaire de paiement</h1>
    
    <form method="POST">
        <div>
            <label>Nom du produit:</label>
            <input type="text" name="item_name" required>
        </div>
        
        <div>
            <label>Prix (XOF):</label>
            <input type="number" name="item_price" required>
        </div>
        
        <div>
            <label>Méthode de paiement:</label>
            <select name="payment_method">
                <option value="">Toutes les méthodes</option>
                <option value="Orange Money">Orange Money</option>
                <option value="Tigo Cash">Tigo Cash</option>
                <option value="Carte Bancaire">Carte Bancaire</option>
                <option value="Wave">Wave</option>
            </select>
        </div>
        
        <div>
            <input type="hidden" name="user_id" value="123">
            <button type="submit">Payer</button>
        </div>
    </form>
</body>
</html>
```

### Créer un paiement (Mobile/API)

```php
<?php
// api/create_mobile_payment.php

require_once '../PayTechClient.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $paytech = new PayTechClient();
    
    $itemName = $input['item_name'];
    $itemPrice = (int)$input['item_price'];
    $refCommand = 'MOBILE_' . uniqid();
    $customField = $input['custom_field'] ?? [];
    $paymentMethod = $input['payment_method'] ?? null;
    
    // Données utilisateur pour le préfillage
    $user = [
        'phone_number' => $input['user_phone'],
        'first_name' => $input['user_first_name'],
        'last_name' => $input['user_last_name']
    ];
    
    $result = $paytech->requestPayment(
        $itemName,
        $itemPrice,
        $refCommand,
        $customField,
        $paymentMethod,
        $user,
        true // Mobile integration
    );
    
    if ($result['success']) {
        // Sauvegarder en base de données
        saveTransaction($refCommand, $result['data']);
        
        echo json_encode([
            'success' => true,
            'redirect_url' => $result['data']['redirect_url'],
            'token' => $result['data']['token']
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la création du paiement',
            'errors' => $result['errors']
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
}
```

## Gestion de la base de données

### Structure de table

```sql
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ref_command VARCHAR(255) UNIQUE NOT NULL,
    token VARCHAR(255),
    amount INT NOT NULL,
    currency VARCHAR(3) DEFAULT 'xof',
    status ENUM('pending', 'completed', 'cancelled', 'failed') DEFAULT 'pending',
    payment_method VARCHAR(100),
    custom_field JSON,
    paytech_data JSON,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_status_created (status, created_at),
    INDEX idx_ref_command (ref_command)
);
```

### Fonctions de base de données

```php
<?php
// database.php

function getDbConnection()
{
    $host = 'localhost';
    $dbname = 'votre_db';
    $username = 'votre_user';
    $password = 'votre_password';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('Erreur de connexion: ' . $e->getMessage());
    }
}

function saveTransaction($refCommand, $paytechData)
{
    $pdo = getDbConnection();
    
    $stmt = $pdo->prepare("
        INSERT INTO transactions (ref_command, token, amount, paytech_data, status) 
        VALUES (?, ?, ?, ?, 'pending')
    ");
    
    $stmt->execute([
        $refCommand,
        $paytechData['token'],
        $paytechData['item_price'] ?? 0,
        json_encode($paytechData)
    ]);
    
    return $pdo->lastInsertId();
}

function updateTransactionStatus($refCommand, $status)
{
    $pdo = getDbConnection();
    
    $stmt = $pdo->prepare("
        UPDATE transactions 
        SET status = ?, updated_at = NOW() 
        WHERE ref_command = ?
    ");
    
    return $stmt->execute([$status, $refCommand]);
}

function getTransaction($refCommand)
{
    $pdo = getDbConnection();
    
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE ref_command = ?");
    $stmt->execute([$refCommand]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
```

## Traitement des notifications IPN

```php
<?php
// ipn.php

require_once 'PayTechClient.php';
require_once 'database.php';

// Désactiver l'affichage des erreurs pour l'IPN
ini_set('display_errors', 0);

// Logger les données reçues
file_put_contents('ipn.log', date('Y-m-d H:i:s') . " - IPN reçu: " . file_get_contents('php://input') . "\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $paytech = new PayTechClient();
        
        // PayTech envoie les données en POST form-encoded
        $data = $_POST;
        
        // Validation de l'IPN (choisir une méthode)
        $isValid = $paytech->validateIPN($data) || $paytech->validateHMAC($data);
        
        if (!$isValid) {
            file_put_contents('ipn.log', date('Y-m-d H:i:s') . " - IPN invalide\n", FILE_APPEND);
            http_response_code(400);
            echo 'Invalid IPN';
            exit;
        }
        
        $refCommand = $data['ref_command'];
        $typeEvent = $data['type_event'];
        
        // Vérifier que la transaction existe
        $transaction = getTransaction($refCommand);
        if (!$transaction) {
            file_put_contents('ipn.log', date('Y-m-d H:i:s') . " - Transaction non trouvée: $refCommand\n", FILE_APPEND);
            http_response_code(404);
            echo 'Transaction not found';
            exit;
        }
        
        // Traiter selon le type d'événement
        switch ($typeEvent) {
            case 'sale_complete':
                updateTransactionStatus($refCommand, 'completed');
                file_put_contents('ipn.log', date('Y-m-d H:i:s') . " - Paiement confirmé: $refCommand\n", FILE_APPEND);
                
                // Logique métier (envoyer email, activer service, etc.)
                processSuccessfulPayment($transaction, $data);
                break;
                
            case 'sale_cancel':
                updateTransactionStatus($refCommand, 'cancelled');
                file_put_contents('ipn.log', date('Y-m-d H:i:s') . " - Paiement annulé: $refCommand\n", FILE_APPEND);
                break;
                
            default:
                file_put_contents('ipn.log', date('Y-m-d H:i:s') . " - Événement non géré: $typeEvent\n", FILE_APPEND);
        }
        
        echo 'OK';
        
    } catch (Exception $e) {
        file_put_contents('ipn.log', date('Y-m-d H:i:s') . " - Erreur: " . $e->getMessage() . "\n", FILE_APPEND);
        http_response_code(500);
        echo 'Error';
    }
} else {
    http_response_code(405);
    echo 'Method not allowed';
}

function processSuccessfulPayment($transaction, $ipnData)
{
    // Exemple de logique métier
    
    // Envoyer un email de confirmation
    $to = 'client@example.com';
    $subject = 'Paiement confirmé - ' . $transaction['ref_command'];
    $message = "Votre paiement de " . number_format($transaction['amount']) . " XOF a été confirmé.";
    mail($to, $subject, $message);
    
    // Activer un service, débloquer un compte, etc.
    // ...
}
```

## Pages de retour

### Page de succès

```php
<?php
// success.php

require_once 'database.php';

$token = $_GET['token'] ?? '';
$refCommand = $_GET['ref_command'] ?? '';

$transaction = null;
if ($refCommand) {
    $transaction = getTransaction($refCommand);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Paiement réussi</title>
    <style>
        .success { color: green; text-align: center; padding: 20px; }
        .info { background: #f0f0f0; padding: 15px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="success">
        <h1>✅ Paiement réussi !</h1>
        
        <?php if ($transaction): ?>
        <div class="info">
            <p><strong>Référence :</strong> <?= htmlspecialchars($transaction['ref_command']) ?></p>
            <p><strong>Montant :</strong> <?= number_format($transaction['amount']) ?> XOF</p>
            <p><strong>Statut :</strong> <?= ucfirst($transaction['status']) ?></p>
        </div>
        <?php endif; ?>
        
        <p>Votre paiement a été traité avec succès.</p>
        <a href="/">Retour à l'accueil</a>
    </div>
</body>
</html>
```

### Page d'annulation

```php
<?php
// cancel.php

require_once 'database.php';

$token = $_GET['token'] ?? '';
$refCommand = $_GET['ref_command'] ?? '';

$transaction = null;
if ($refCommand) {
    $transaction = getTransaction($refCommand);
    // Marquer comme annulé si pas déjà fait
    if ($transaction && $transaction['status'] === 'pending') {
        updateTransactionStatus($refCommand, 'cancelled');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Paiement annulé</title>
    <style>
        .cancel { color: red; text-align: center; padding: 20px; }
        .info { background: #f0f0f0; padding: 15px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="cancel">
        <h1>❌ Paiement annulé</h1>
        
        <?php if ($transaction): ?>
        <div class="info">
            <p><strong>Référence :</strong> <?= htmlspecialchars($transaction['ref_command']) ?></p>
            <p><strong>Montant :</strong> <?= number_format($transaction['amount']) ?> XOF</p>
        </div>
        <?php endif; ?>
        
        <p>Votre paiement a été annulé.</p>
        <a href="/">Retour à l'accueil</a>
        <a href="/create_payment.php">Réessayer</a>
    </div>
</body>
</html>
```

## Gestion des erreurs

```php
<?php
// ErrorHandler.php

class PayTechErrorHandler
{
    public static function handleApiError($response)
    {
        $errorCodes = [
            '01' => 'Paramètres manquants',
            '02' => 'Clés API invalides',
            '03' => 'Montant invalide',
            '04' => 'Devise non supportée',
            '05' => 'Erreur de communication'
        ];
        
        if (isset($response['error_code'])) {
            $errorCode = $response['error_code'];
            $errorMessage = $errorCodes[$errorCode] ?? 'Erreur inconnue';
            
            return [
                'success' => false,
                'error_code' => $errorCode,
                'error_message' => $errorMessage
            ];
        }
        
        return ['success' => true];
    }
    
    public static function logError($message, $context = [])
    {
        $logEntry = date('Y-m-d H:i:s') . " - $message";
        if (!empty($context)) {
            $logEntry .= " - Context: " . json_encode($context);
        }
        $logEntry .= "\n";
        
        file_put_contents('paytech_errors.log', $logEntry, FILE_APPEND);
    }
}
```

## Tests

```php
<?php
// test.php

require_once 'PayTechClient.php';

function testPayTechIntegration()
{
    $paytech = new PayTechClient();
    
    // Test de création de paiement
    echo "Test de création de paiement...\n";
    
    $result = $paytech->requestPayment(
        'Produit Test',
        1000,
        'TEST_' . uniqid(),
        ['test' => true],
        'Orange Money'
    );
    
    if ($result['success']) {
        echo "✅ Paiement créé avec succès\n";
        echo "Token: " . $result['data']['token'] . "\n";
        echo "URL: " . $result['data']['redirect_url'] . "\n";
    } else {
        echo "❌ Erreur: " . implode(', ', $result['errors']) . "\n";
    }
    
    // Test de validation IPN
    echo "\nTest de validation IPN...\n";
    
    $testIPN = [
        'api_key_sha256' => hash('sha256', PAYTECH_API_KEY),
        'api_secret_sha256' => hash('sha256', PAYTECH_SECRET_KEY)
    ];
    
    if ($paytech->validateIPN($testIPN)) {
        echo "✅ Validation IPN réussie\n";
    } else {
        echo "❌ Validation IPN échouée\n";
    }
}

// Exécuter les tests
if (php_sapi_name() === 'cli') {
    testPayTechIntegration();
}
```

## Sécurité et bonnes pratiques

### Configuration sécurisée

```php
<?php
// secure_config.php

// Ne jamais exposer les clés en dur dans le code
$config = [
    'paytech' => [
        'api_key' => $_ENV['PAYTECH_API_KEY'] ?? getenv('PAYTECH_API_KEY'),
        'secret_key' => $_ENV['PAYTECH_SECRET_KEY'] ?? getenv('PAYTECH_SECRET_KEY'),
        'env' => $_ENV['PAYTECH_ENV'] ?? 'test'
    ]
];

// Validation des clés
if (empty($config['paytech']['api_key']) || empty($config['paytech']['secret_key'])) {
    throw new Exception('Clés PayTech manquantes');
}
```

### Rate limiting

```php
<?php
// RateLimiter.php

class RateLimiter
{
    private $maxRequests = 10;
    private $timeWindow = 3600; // 1 heure
    
    public function isAllowed($clientIp)
    {
        $key = "paytech_rate_limit_$clientIp";
        $requests = apcu_fetch($key) ?: 0;
        
        if ($requests >= $this->maxRequests) {
            return false;
        }
        
        apcu_store($key, $requests + 1, $this->timeWindow);
        return true;
    }
}

// Utilisation
$rateLimiter = new RateLimiter();
if (!$rateLimiter->isAllowed($_SERVER['REMOTE_ADDR'])) {
    http_response_code(429);
    echo 'Trop de requêtes';
    exit;
}
```

## Ressources supplémentaires

- [Documentation PHP cURL](https://www.php.net/manual/en/book.curl.php)
- [PDO Documentation](https://www.php.net/manual/en/book.pdo.php)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)
- [Support PayTech](mailto:support@paytech.sn)


# Paiement avec redirection

Guide détaillé pour implémenter les paiements avec redirection PayTech.

## 🎯 Principe

Le paiement avec redirection est la méthode la plus simple pour intégrer PayTech :

1. **Créer** une demande de paiement
2. **Rediriger** le client vers PayTech
3. **Traiter** le retour du client
4. **Confirmer** via les notifications IPN

## 🚀 Implémentation rapide

### 1. Demande de paiement

```javascript
// Frontend - Créer la demande
const paymentData = {
    item_name: "Achat produit",
    item_price: 10000,
    currency: "xof",
    ref_command: "CMD_" + Date.now(),
    command_name: "Commande client",
    env: "test", // ou "prod"
    success_url: "https://monsite.com/success",
    cancel_url: "https://monsite.com/cancel",
    ipn_url: "https://monsite.com/ipn"
};

fetch('/api/create-payment', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(paymentData)
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        // Rediriger vers PayTech
        window.location.href = data.redirect_url;
    }
});
```

### 2. Backend - Créer la demande

```php
<?php
// Backend PHP - Traiter la demande
function createPayTechPayment($data) {
    $url = 'https://paytech.sn/api/payment/request-payment';
    
    $payload = [
        'item_name' => $data['item_name'],
        'item_price' => $data['item_price'],
        'currency' => $data['currency'],
        'ref_command' => $data['ref_command'],
        'command_name' => $data['command_name'],
        'env' => PAYTECH_ENV,
        'success_url' => $data['success_url'],
        'cancel_url' => $data['cancel_url'],
        'ipn_url' => $data['ipn_url']
    ];
    
    $headers = [
        'API_KEY: ' . PAYTECH_API_KEY,
        'API_SECRET: ' . PAYTECH_SECRET_KEY,
        'Content-Type: application/json'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}
?>
```

## 🔧 Configuration avancée

### Paramètres optionnels

```javascript
const advancedPayment = {
    // Paramètres de base
    item_name: "Produit premium",
    item_price: 25000,
    currency: "xof",
    ref_command: "CMD_PREMIUM_" + Date.now(),
    
    // Paramètres avancés
    custom_field: JSON.stringify({
        customer_id: "12345",
        order_notes: "Livraison express",
        metadata: { source: "mobile_app" }
    }),
    
    // Cibler une méthode spécifique
    target_payment: "Orange Money", // Redirection directe
    
    // Ou proposer plusieurs méthodes
    target_payment: "Orange Money,Tigo Cash,Wave",
    
    // URLs personnalisées
    success_url: "https://monsite.com/payment/success?order=12345",
    cancel_url: "https://monsite.com/payment/cancel?order=12345",
    ipn_url: "https://monsite.com/webhook/paytech"
};
```

### Préfillage pour mobile

```javascript
// Pour les applications mobiles
const mobilePayment = {
    // ... paramètres de base
    
    // URLs mobiles spéciales
    success_url: "https://paytech.sn/mobile/success",
    cancel_url: "https://paytech.sn/mobile/cancel",
    
    // Préfillage utilisateur (ajouté à l'URL de redirection)
    user_info: {
        phone_number: "+221701234567",
        full_name: "John Doe"
    }
};

// Le backend ajoute les paramètres à l'URL de redirection
function addUserParams(redirectUrl, userInfo) {
    const params = new URLSearchParams({
        pn: userInfo.phone_number,        // +221701234567
        nn: userInfo.phone_number.slice(4), // 701234567
        fn: userInfo.full_name,           // John Doe
        tp: "Orange Money",               // Méthode ciblée
        nac: 1                           // Auto-submit
    });
    
    return `${redirectUrl}?${params.toString()}`;
}
```

## 📱 Gestion des retours

### Page de succès

```html
<!-- success.html -->
<!DOCTYPE html>
<html>
<head>
    <title>Paiement réussi</title>
</head>
<body>
    <div class="success-container">
        <h1>✅ Paiement réussi !</h1>
        <p>Votre commande <span id="order-ref"></span> a été confirmée.</p>
        <p>Vous recevrez un email de confirmation sous peu.</p>
        <a href="/" class="btn">Retour à l'accueil</a>
    </div>
    
    <script>
        // Récupérer les paramètres de l'URL
        const urlParams = new URLSearchParams(window.location.search);
        const orderRef = urlParams.get('ref_command');
        
        if (orderRef) {
            document.getElementById('order-ref').textContent = orderRef;
            
            // Optionnel : Vérifier le statut côté serveur
            fetch(`/api/verify-payment/${orderRef}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.verified) {
                        // Rediriger vers une page d'erreur
                        window.location.href = '/payment/error';
                    }
                });
        }
    </script>
</body>
</html>
```

### Page d'annulation

```html
<!-- cancel.html -->
<!DOCTYPE html>
<html>
<head>
    <title>Paiement annulé</title>
</head>
<body>
    <div class="cancel-container">
        <h1>❌ Paiement annulé</h1>
        <p>Votre paiement a été annulé.</p>
        <p>Aucun montant n'a été débité.</p>
        
        <div class="actions">
            <a href="/cart" class="btn btn-primary">Retour au panier</a>
            <a href="/" class="btn btn-secondary">Accueil</a>
        </div>
    </div>
    
    <script>
        // Optionnel : Analytics
        gtag('event', 'payment_cancelled', {
            'event_category': 'ecommerce',
            'event_label': 'paytech'
        });
    </script>
</body>
</html>
```

## 🔔 Traitement des IPN

### Endpoint IPN

```php
<?php
// ipn.php - Traiter les notifications PayTech
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// Récupérer les données IPN
$ipnData = $_POST;

// Log pour débogage
error_log('IPN PayTech reçu: ' . print_r($ipnData, true));

// Valider la signature
if (!validateIPNSignature($ipnData)) {
    http_response_code(400);
    exit('Invalid signature');
}

// Traiter selon le type d'événement
switch ($ipnData['type_event']) {
    case 'sale_complete':
        handlePaymentSuccess($ipnData);
        break;
        
    case 'sale_failed':
        handlePaymentFailure($ipnData);
        break;
        
    default:
        error_log('Type d\'événement IPN inconnu: ' . $ipnData['type_event']);
}

// Répondre à PayTech
http_response_code(200);
echo json_encode(['status' => 'ok']);

function validateIPNSignature($data) {
    // Méthode 1: Validation SHA256
    $expectedApiKey = hash('sha256', PAYTECH_API_KEY);
    $expectedSecret = hash('sha256', PAYTECH_SECRET_KEY);
    
    if ($data['api_key_sha256'] === $expectedApiKey && 
        $data['api_secret_sha256'] === $expectedSecret) {
        return true;
    }
    
    // Méthode 2: Validation HMAC (recommandée)
    if (isset($data['hmac_compute'])) {
        $message = $data['item_price'] . '|' . $data['ref_command'] . '|' . PAYTECH_API_KEY;
        $expectedHmac = hash_hmac('sha256', $message, PAYTECH_SECRET_KEY);
        
        return hash_equals($expectedHmac, $data['hmac_compute']);
    }
    
    return false;
}

function handlePaymentSuccess($data) {
    // Mettre à jour la commande
    $orderId = $data['ref_command'];
    $amount = $data['item_price'];
    
    // Vérifier que la commande existe
    $order = getOrder($orderId);
    if (!$order) {
        error_log('Commande introuvable: ' . $orderId);
        return;
    }
    
    // Vérifier le montant
    if ($order['amount'] != $amount) {
        error_log('Montant incorrect pour ' . $orderId . ': attendu ' . $order['amount'] . ', reçu ' . $amount);
        return;
    }
    
    // Marquer comme payé
    updateOrderStatus($orderId, 'paid', $data);
    
    // Envoyer email de confirmation
    sendConfirmationEmail($order);
    
    // Déclencher les actions post-paiement
    triggerPostPaymentActions($order, $data);
}

function handlePaymentFailure($data) {
    $orderId = $data['ref_command'];
    
    // Marquer comme échoué
    updateOrderStatus($orderId, 'failed', $data);
    
    // Optionnel : Notifier le client
    sendPaymentFailureNotification($orderId);
}
?>
```

## 🛡️ Sécurité

### Validation côté serveur

```php
<?php
// Toujours valider côté serveur
function verifyPaymentOnReturn($refCommand) {
    // Ne jamais faire confiance aux paramètres GET/POST
    // Toujours vérifier via l'API ou attendre l'IPN
    
    $order = getOrder($refCommand);
    
    // Vérifier via l'API PayTech
    $status = checkPayTechStatus($order['paytech_token']);
    
    if ($status['success'] && $status['status'] === 'completed') {
        return true;
    }
    
    return false;
}

function checkPayTechStatus($token) {
    $url = "https://paytech.sn/api/payment/check/{$token}";
    
    $headers = [
        'API_KEY: ' . PAYTECH_API_KEY,
        'API_SECRET: ' . PAYTECH_SECRET_KEY
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}
?>
```

### Protection CSRF

```php
<?php
// Ajouter un token CSRF
session_start();

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && 
           hash_equals($_SESSION['csrf_token'], $token);
}

// Dans le formulaire de paiement
$csrfToken = generateCSRFToken();
?>

<form action="/create-payment" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
    <!-- autres champs -->
</form>
```

## 📊 Monitoring

### Logs structurés

```php
<?php
function logPaymentEvent($event, $data) {
    $logEntry = [
        'timestamp' => date('c'),
        'event' => $event,
        'ref_command' => $data['ref_command'] ?? null,
        'amount' => $data['amount'] ?? null,
        'status' => $data['status'] ?? null,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
    ];
    
    error_log('PAYTECH_EVENT: ' . json_encode($logEntry));
}

// Utilisation
logPaymentEvent('payment_initiated', $paymentData);
logPaymentEvent('payment_completed', $ipnData);
logPaymentEvent('payment_failed', $ipnData);
?>
```

### Métriques importantes

```javascript
// Côté frontend - Analytics
function trackPaymentEvent(event, data) {
    // Google Analytics
    gtag('event', event, {
        'event_category': 'payment',
        'event_label': 'paytech',
        'value': data.amount
    });
    
    // Métriques personnalisées
    fetch('/api/metrics', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            event: event,
            timestamp: Date.now(),
            data: data
        })
    });
}

// Utilisation
trackPaymentEvent('payment_initiated', { amount: 10000, method: 'orange_money' });
trackPaymentEvent('payment_completed', { amount: 10000, duration: 45000 });
```

## 🔧 Débogage

### Mode debug

```php
<?php
// Configuration debug
define('PAYTECH_DEBUG', true);

function debugLog($message, $data = null) {
    if (PAYTECH_DEBUG) {
        $logMessage = '[PAYTECH DEBUG] ' . $message;
        if ($data) {
            $logMessage .= ': ' . print_r($data, true);
        }
        error_log($logMessage);
    }
}

// Utilisation
debugLog('Création demande paiement', $paymentData);
debugLog('Réponse PayTech', $response);
debugLog('IPN reçu', $_POST);
?>
```

### Outils de test

```bash
# Tester l'endpoint IPN
curl -X POST https://monsite.com/ipn \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "type_event=sale_complete&ref_command=TEST_123&item_price=1000&api_key_sha256=..."

# Vérifier les logs
tail -f /var/log/apache2/error.log | grep PAYTECH
```

## 📋 Checklist d'intégration

### Avant la mise en production

- [ ] Tests avec cartes de test
- [ ] Tests avec numéros mobiles de test
- [ ] Validation des IPN
- [ ] Pages de succès/annulation fonctionnelles
- [ ] Logs configurés
- [ ] Monitoring en place
- [ ] Clés de production configurées
- [ ] URLs HTTPS vérifiées
- [ ] Gestion d'erreurs implémentée
- [ ] Documentation équipe rédigée

### Après la mise en production

- [ ] Monitoring des transactions
- [ ] Vérification des IPN
- [ ] Tests de bout en bout
- [ ] Formation support client
- [ ] Sauvegarde des configurations


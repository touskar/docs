# IPN - Fonctionnement des notifications

Le système IPN (Instant Payment Notification) de PayTech permet à votre serveur de recevoir des notifications en temps réel lorsqu'un paiement est confirmé. Cette section explique le fonctionnement complet du système IPN et son implémentation.

## Qu'est-ce que l'IPN ?

L'IPN (Instant Payment Notification) est un système de notification automatique qui informe votre serveur du statut d'un paiement dès que celui-ci est traité par PayTech. Contrairement aux redirections utilisateur qui peuvent être interrompues, l'IPN garantit que votre système sera notifié même si l'utilisateur ferme son navigateur.

## Principe de fonctionnement

### Flux de notification

1. **Paiement effectué** : Le client finalise son paiement sur la plateforme PayTech
2. **Traitement** : PayTech traite le paiement avec le fournisseur de service
3. **Confirmation** : Une fois le paiement confirmé, PayTech prépare la notification
4. **Envoi IPN** : PayTech envoie une requête POST à votre URL IPN
5. **Validation** : Votre serveur valide l'authenticité de la notification
6. **Traitement** : Votre système met à jour le statut de la commande
7. **Réponse** : Votre serveur répond "IPN OK" pour confirmer la réception

### Schéma du processus

```
Client → PayTech → Fournisseur de paiement
                ↓ (Confirmation)
Votre serveur ← PayTech (Notification IPN)
                ↓ (Réponse "IPN OK")
              PayTech
```

## Configuration de l'URL IPN

### URL IPN globale

Configurez votre URL IPN par défaut dans votre dashboard PayTech :

1. Connectez-vous à [paytech.sn/app](https://paytech.sn/app)
2. Allez dans **Paramètres > URLs de callback**
3. Définissez votre **URL IPN** : `https://votre-site.com/webhooks/paytech`

> ⚠️ **Important** : Seules les URLs HTTPS sont acceptées pour des raisons de sécurité.

### URL IPN spécifique par transaction

Vous pouvez également spécifier une URL IPN différente pour chaque transaction :

```json
{
  "item_name": "Produit spécial",
  "item_price": 5000,
  "ref_command": "CMD_001",
  "command_name": "Commande spéciale",
  "ipn_url": "https://votre-site.com/webhooks/paytech/special"
}
```

## Structure de la notification IPN

### Données reçues

Votre endpoint IPN recevra une requête POST avec les données suivantes :

```json
{
  "type_event": "sale_complete",
  "client_phone": "221771234567",
  "payment_method": "Orange Money",
  "item_name": "iPhone 13 Pro",
  "item_price": 650000,
  "ref_command": "CMD_20241106_001",
  "command_name": "Achat iPhone 13 Pro 256GB",
  "currency": "XOF",
  "env": "prod",
  "custom_field": "{\"user_id\":12345,\"plan\":\"premium\"}",
  "token": "4fe7bb6bedbd94689e89",
  "api_key_sha256": "dacbde6382f4bf6ecf4dcec0624712abec1c02b7e5514dad23fdf1242c70d9b5",
  "api_secret_sha256": "91b1ae073d5edd8f3d71ac2fb88c90018c70c9b30993513de15b1757958ab0d3"
}
```

### Description des champs

| Champ | Type | Description |
|-------|------|-------------|
| `type_event` | string | Type d'événement : "sale_complete" |
| `client_phone` | string | Numéro de téléphone du client (si disponible) |
| `payment_method` | string | Méthode de paiement utilisée |
| `item_name` | string | Nom du produit/service |
| `item_price` | number | Prix de la transaction |
| `ref_command` | string | Référence de commande du marchand |
| `command_name` | string | Description de la commande |
| `currency` | string | Code de la devise |
| `env` | string | Environnement : "test" ou "prod" |
| `custom_field` | string | Données personnalisées (JSON) |
| `token` | string | Token de la transaction |
| `api_key_sha256` | string | Hash SHA256 de votre API_KEY |
| `api_secret_sha256` | string | Hash SHA256 de votre API_SECRET |

## Validation de l'authenticité

### Principe de validation

Pour garantir que la notification provient bien de PayTech, vous devez valider les hash SHA256 des clés API :

```php
<?php
function validateIPNFromPayTech($requestData, $apiKey, $apiSecret) {
    $receivedApiKeyHash = $requestData['api_key_sha256'];
    $receivedSecretHash = $requestData['api_secret_sha256'];
    
    $expectedApiKeyHash = hash('sha256', $apiKey);
    $expectedSecretHash = hash('sha256', $apiSecret);
    
    return ($receivedApiKeyHash === $expectedApiKeyHash && 
            $receivedSecretHash === $expectedSecretHash);
}
?>
```

### Implémentation complète PHP

```php
<?php
// webhook.php - Endpoint IPN PayTech

// Configuration
define('PAYTECH_API_KEY', 'votre_api_key');
define('PAYTECH_API_SECRET', 'votre_api_secret');

// Fonction de validation
function validateIPNFromPayTech($data) {
    $receivedApiKeyHash = $data['api_key_sha256'] ?? '';
    $receivedSecretHash = $data['api_secret_sha256'] ?? '';
    
    $expectedApiKeyHash = hash('sha256', PAYTECH_API_KEY);
    $expectedSecretHash = hash('sha256', PAYTECH_API_SECRET);
    
    return ($receivedApiKeyHash === $expectedApiKeyHash && 
            $receivedSecretHash === $expectedSecretHash);
}

// Fonction de logging
function logIPN($message, $data = null) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message";
    if ($data) {
        $logMessage .= " - Data: " . json_encode($data);
    }
    error_log($logMessage . "\n", 3, '/var/log/paytech_ipn.log');
}

// Traitement de l'IPN
try {
    // Récupération des données POST
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data) {
        logIPN('IPN Error: Invalid JSON data', $input);
        http_response_code(400);
        exit('Invalid JSON');
    }
    
    logIPN('IPN Received', $data);
    
    // Validation de l'authenticité
    if (!validateIPNFromPayTech($data)) {
        logIPN('IPN Error: Invalid authentication');
        http_response_code(403);
        exit('IPN KO NOT FROM PAYTECH');
    }
    
    // Vérification du type d'événement
    if ($data['type_event'] !== 'sale_complete') {
        logIPN('IPN Error: Unknown event type', $data['type_event']);
        http_response_code(400);
        exit('Unknown event type');
    }
    
    // Extraction des données importantes
    $refCommand = $data['ref_command'];
    $amount = $data['item_price'];
    $paymentMethod = $data['payment_method'];
    $currency = $data['currency'];
    $customField = $data['custom_field'];
    
    // Traitement métier
    $success = processPaymentConfirmation($refCommand, $amount, $paymentMethod, $customField);
    
    if ($success) {
        logIPN('IPN Success: Payment processed', $refCommand);
        echo 'IPN OK';
    } else {
        logIPN('IPN Error: Payment processing failed', $refCommand);
        http_response_code(500);
        exit('Processing failed');
    }
    
} catch (Exception $e) {
    logIPN('IPN Exception: ' . $e->getMessage());
    http_response_code(500);
    exit('Internal error');
}

function processPaymentConfirmation($refCommand, $amount, $paymentMethod, $customField) {
    try {
        // Connexion à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=votre_db', 'user', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Vérification que la commande existe et n'est pas déjà payée
        $stmt = $pdo->prepare("SELECT id, status, amount FROM orders WHERE ref_command = ?");
        $stmt->execute([$refCommand]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            logIPN('Order not found', $refCommand);
            return false;
        }
        
        if ($order['status'] === 'paid') {
            logIPN('Order already paid', $refCommand);
            return true; // Déjà traité, mais on retourne true
        }
        
        // Vérification du montant
        if ($order['amount'] != $amount) {
            logIPN('Amount mismatch', [
                'expected' => $order['amount'],
                'received' => $amount
            ]);
            return false;
        }
        
        // Mise à jour du statut de la commande
        $stmt = $pdo->prepare("
            UPDATE orders 
            SET status = 'paid', 
                payment_method = ?, 
                paid_at = NOW(),
                paytech_data = ?
            WHERE ref_command = ?
        ");
        
        $paytechData = json_encode([
            'payment_method' => $paymentMethod,
            'custom_field' => $customField,
            'processed_at' => date('Y-m-d H:i:s')
        ]);
        
        $stmt->execute([$paymentMethod, $paytechData, $refCommand]);
        
        // Actions post-paiement (email, livraison, etc.)
        triggerPostPaymentActions($order['id']);
        
        return true;
        
    } catch (PDOException $e) {
        logIPN('Database error: ' . $e->getMessage());
        return false;
    }
}

function triggerPostPaymentActions($orderId) {
    // Envoi d'email de confirmation
    // Déclenchement de la livraison
    // Mise à jour des stocks
    // etc.
}
?>
```

### Implémentation Node.js

```javascript
const express = require('express');
const crypto = require('crypto');
const mysql = require('mysql2/promise');

const app = express();
app.use(express.json());

// Configuration
const PAYTECH_API_KEY = process.env.PAYTECH_API_KEY;
const PAYTECH_API_SECRET = process.env.PAYTECH_API_SECRET;

// Fonction de validation
function validateIPNFromPayTech(data) {
    const receivedApiKeyHash = data.api_key_sha256 || '';
    const receivedSecretHash = data.api_secret_sha256 || '';
    
    const expectedApiKeyHash = crypto.createHash('sha256')
        .update(PAYTECH_API_KEY)
        .digest('hex');
    const expectedSecretHash = crypto.createHash('sha256')
        .update(PAYTECH_API_SECRET)
        .digest('hex');
    
    return (receivedApiKeyHash === expectedApiKeyHash && 
            receivedSecretHash === expectedSecretHash);
}

// Endpoint IPN
app.post('/webhooks/paytech', async (req, res) => {
    try {
        const data = req.body;
        
        console.log('IPN Received:', JSON.stringify(data, null, 2));
        
        // Validation de l'authenticité
        if (!validateIPNFromPayTech(data)) {
            console.error('IPN Error: Invalid authentication');
            return res.status(403).send('IPN KO NOT FROM PAYTECH');
        }
        
        // Vérification du type d'événement
        if (data.type_event !== 'sale_complete') {
            console.error('IPN Error: Unknown event type:', data.type_event);
            return res.status(400).send('Unknown event type');
        }
        
        // Traitement du paiement
        const success = await processPaymentConfirmation(data);
        
        if (success) {
            console.log('IPN Success: Payment processed for', data.ref_command);
            res.send('IPN OK');
        } else {
            console.error('IPN Error: Payment processing failed for', data.ref_command);
            res.status(500).send('Processing failed');
        }
        
    } catch (error) {
        console.error('IPN Exception:', error);
        res.status(500).send('Internal error');
    }
});

async function processPaymentConfirmation(data) {
    const connection = await mysql.createConnection({
        host: 'localhost',
        user: 'your_user',
        password: 'your_password',
        database: 'your_database'
    });
    
    try {
        await connection.beginTransaction();
        
        // Vérification de la commande
        const [orders] = await connection.execute(
            'SELECT id, status, amount FROM orders WHERE ref_command = ?',
            [data.ref_command]
        );
        
        if (orders.length === 0) {
            console.error('Order not found:', data.ref_command);
            return false;
        }
        
        const order = orders[0];
        
        if (order.status === 'paid') {
            console.log('Order already paid:', data.ref_command);
            return true;
        }
        
        // Vérification du montant
        if (order.amount !== data.item_price) {
            console.error('Amount mismatch:', {
                expected: order.amount,
                received: data.item_price
            });
            return false;
        }
        
        // Mise à jour de la commande
        await connection.execute(`
            UPDATE orders 
            SET status = 'paid', 
                payment_method = ?, 
                paid_at = NOW(),
                paytech_data = ?
            WHERE ref_command = ?
        `, [
            data.payment_method,
            JSON.stringify(data),
            data.ref_command
        ]);
        
        await connection.commit();
        
        // Actions post-paiement
        await triggerPostPaymentActions(order.id);
        
        return true;
        
    } catch (error) {
        await connection.rollback();
        console.error('Database error:', error);
        return false;
    } finally {
        await connection.end();
    }
}

async function triggerPostPaymentActions(orderId) {
    // Implémentation des actions post-paiement
    console.log('Triggering post-payment actions for order:', orderId);
}

app.listen(3000, () => {
    console.log('IPN server listening on port 3000');
});
```

## Gestion des erreurs et retry

### Mécanisme de retry PayTech

PayTech implémente un système de retry automatique :

- **1er essai** : Immédiatement après confirmation du paiement
- **2ème essai** : 5 minutes après le premier échec
- **3ème essai** : 15 minutes après le deuxième échec
- **4ème essai** : 1 heure après le troisième échec
- **5ème essai** : 6 heures après le quatrième échec

### Réponses attendues

Votre endpoint doit répondre avec :

**Succès** : HTTP 200 avec le texte "IPN OK"
```
HTTP/1.1 200 OK
Content-Type: text/plain

IPN OK
```

**Échec temporaire** : HTTP 5xx pour déclencher un retry
```
HTTP/1.1 500 Internal Server Error
Content-Type: text/plain

Temporary error, please retry
```

**Échec permanent** : HTTP 4xx pour arrêter les retries
```
HTTP/1.1 400 Bad Request
Content-Type: text/plain

Invalid data format
```

## Sécurité et bonnes pratiques

### Validation stricte

```php
// Validation complète des données IPN
function validateIPNData($data) {
    $required = ['type_event', 'ref_command', 'item_price', 'api_key_sha256', 'api_secret_sha256'];
    
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            return false;
        }
    }
    
    // Validation du format de la référence
    if (!preg_match('/^[A-Za-z0-9_-]+$/', $data['ref_command'])) {
        return false;
    }
    
    // Validation du montant
    if (!is_numeric($data['item_price']) || $data['item_price'] <= 0) {
        return false;
    }
    
    return true;
}
```

### Protection contre les attaques

```php
// Limitation du taux de requêtes
function checkRateLimit($ip) {
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    
    $key = "ipn_rate_limit:$ip";
    $current = $redis->incr($key);
    
    if ($current === 1) {
        $redis->expire($key, 60); // 1 minute
    }
    
    return $current <= 10; // Max 10 requêtes par minute
}

// Utilisation
$clientIP = $_SERVER['REMOTE_ADDR'];
if (!checkRateLimit($clientIP)) {
    http_response_code(429);
    exit('Rate limit exceeded');
}
```

### Idempotence

```php
// Gestion de l'idempotence avec Redis
function isIPNAlreadyProcessed($token, $refCommand) {
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    
    $key = "ipn_processed:$token:$refCommand";
    
    if ($redis->exists($key)) {
        return true;
    }
    
    // Marquer comme traité (expire après 24h)
    $redis->setex($key, 86400, '1');
    return false;
}
```

## Tests et debugging

### Test de votre endpoint IPN

```bash
# Test avec cURL
curl -X POST https://votre-site.com/webhooks/paytech \
  -H "Content-Type: application/json" \
  -d '{
    "type_event": "sale_complete",
    "ref_command": "TEST_CMD_001",
    "item_price": 1000,
    "payment_method": "Test",
    "api_key_sha256": "hash_de_votre_api_key",
    "api_secret_sha256": "hash_de_votre_api_secret"
  }'
```

### Logs de debugging

```php
// Logging détaillé pour le debugging
function debugLog($message, $data = null) {
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] DEBUG: $message";
        if ($data) {
            $logEntry .= "\nData: " . print_r($data, true);
        }
        file_put_contents('/tmp/paytech_debug.log', $logEntry . "\n", FILE_APPEND);
    }
}
```

---

> **Important** : Testez toujours votre endpoint IPN en mode test avant de passer en production. Utilisez des outils comme ngrok pour exposer votre serveur local pendant le développement.


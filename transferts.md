# Transferts PayTech

PayTech propose un système de transfert d'argent qui permet d'envoyer des fonds directement vers les comptes de mobile money des bénéficiaires. Cette section détaille l'utilisation de l'API de transfert et la gestion des notifications IPN.

## Vue d'ensemble

Le système de transfert PayTech permet de :

- **Envoyer de l'argent** vers les comptes Orange Money, Wave, Tigo Cash, etc.
- **Recevoir des notifications IPN** en temps réel sur le statut des transferts
- **Gérer les échecs** et les tentatives de transfert
- **Suivre les frais** et commissions appliqués
- **Valider l'authenticité** des notifications avec HMAC ou SHA256

## Services de transfert supportés

PayTech supporte un large éventail de services de transfert d'argent à travers l'Afrique de l'Ouest :

### Sénégal
- **Orange Money** - Service de mobile money Orange
- **Wave** - Portefeuille mobile Wave
- **Tigo Cash** - Service Tigo Cash
- **Wizall** - Portefeuille mobile Wizall
- **PayTech** - Transferts internes PayTech
- **Free Money** - Service Free Money
- **Emoney** - Portefeuille électronique Emoney
- **Yup** - Service de paiement Yup
- **Joni Joni** - Portefeuille mobile Joni Joni

### Côte d'Ivoire
- **Orange Money CI** - Orange Money Côte d'Ivoire
- **Wave CI** - Wave Côte d'Ivoire
- **Mtn Money CI** - MTN Mobile Money Côte d'Ivoire
- **Moov Money CI** - Moov Money Côte d'Ivoire

### Mali
- **Orange Money ML** - Orange Money Mali
- **Moov Money ML** - Moov Money Mali

### Bénin
- **Moov Money BJ** - Moov Money Bénin
- **Mtn Money BJ** - MTN Mobile Money Bénin

### Services internationaux
- **Carte Bancaire** - Transferts par carte bancaire
- **Wari** - Réseau de transfert Wari
- **Poste Cash** - Service postal de transfert
- **PayPal** - Transferts PayPal

### Configuration par service

Chaque service a ses propres spécificités :

#### Orange Money (Sénégal)
- **Service ID** : `ORANGE_MONEY_SN_API_CASH_IN`
- **Devise** : XOF (Franc CFA)
- **Frais** : Variables selon le montant
- **Numéros supportés** : 77xxx xxxx, 78xxx xxxx

#### Wave (Sénégal)
- **Service ID** : `WAVE_SN_API_CASH_IN`
- **Devise** : XOF (Franc CFA)
- **Frais** : 1% du montant transféré
- **Numéros supportés** : 77xxx xxxx, 78xxx xxxx

#### Tigo Cash (Sénégal)
- **Service ID** : `TIGO_CASH_SN_API_CASH_IN`
- **Devise** : XOF (Franc CFA)
- **Frais** : Variables selon le montant
- **Numéros supportés** : 76xxx xxxx

#### Orange Money CI (Côte d'Ivoire)
- **Service ID** : `ORANGE_MONEY_CI_API_CASH_IN`
- **Devise** : XOF (Franc CFA)
- **Frais** : Variables selon le montant
- **Numéros supportés** : Numéros ivoiriens

#### MTN Money CI (Côte d'Ivoire)
- **Service ID** : `MTN_MONEY_CI_API_CASH_IN`
- **Devise** : XOF (Franc CFA)
- **Frais** : Variables selon le montant
- **Numéros supportés** : Numéros MTN CI

#### Wave CI (Côte d'Ivoire)
- **Service ID** : `WAVE_CI_API_CASH_IN`
- **Devise** : XOF (Franc CFA)
- **Frais** : Variables selon le montant
- **Numéros supportés** : Numéros ivoiriens

### Liste complète des services

```javascript
const TRANSFER_SERVICES = [
    'Orange Money',
    'Orange Money CI',
    'Orange Money ML',
    'Mtn Money CI',
    'Moov Money CI',
    'Moov Money ML',
    'Wave',
    'Wave CI',
    'Wizall',
    'PayTech',
    'Carte Bancaire',
    'Wari',
    'Poste Cash',
    'PayPal',
    'Emoney',
    'Tigo Cash',
    'Yup',
    'Joni Joni',
    'Free Money',
    'Moov Money BJ',
    'Mtn Money BJ'
];
```

## Demande de transfert

### Endpoint
```
POST https://paytech.sn/api/transfer/request
```

### Headers requis
```http
Content-Type: application/json
API-KEY: votre_api_key
API-SECRET: votre_api_secret
```

### Paramètres de la requête

| Paramètre | Type | Requis | Description |
|-----------|------|--------|-------------|
| `external_id` | string | Oui | Identifiant unique de votre système |
| `amount` | integer | Oui | Montant en XOF (centimes) |
| `service_items_id` | string | Oui | ID du service de transfert |
| `destination_number` | string | Oui | Numéro du bénéficiaire |
| `env` | string | Oui | Environnement (`test` ou `prod`) |

### Exemple de requête

```bash
curl -X POST https://paytech.sn/api/transfer/request \
  -H "Content-Type: application/json" \
  -H "API-KEY: votre_api_key" \
  -H "API-SECRET: votre_api_secret" \
  -d '{
    "external_id": "TRANSFER_001",
    "amount": 50000,
    "service_items_id": "WAVE_SN_API_CASH_IN",
    "destination_number": "772457199",
    "env": "test"
  }'
```

### Exemple PHP

```php
<?php
function requestTransfer($transferData) {
    $url = 'https://paytech.sn/api/transfer/request';
    
    $headers = [
        'Content-Type: application/json',
        'API-KEY: ' . PAYTECH_API_KEY,
        'API-SECRET: ' . PAYTECH_API_SECRET
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($transferData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        return json_decode($response, true);
    } else {
        throw new Exception('Transfer API Error: ' . $response);
    }
}

// Exemple d'utilisation
$transferData = [
    'external_id' => 'TRANSFER_' . time(),
    'amount' => 50000, // 500 XOF
    'service_items_id' => 'WAVE_SN_API_CASH_IN',
    'destination_number' => '772457199',
    'env' => 'test'
];

try {
    $result = requestTransfer($transferData);
    echo "Transfert initié: " . $result['id_transfer'];
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
```

### Réponse de succès

```json
{
    "success": true,
    "id_transfer": "PYT-DEPOT-2QV4WGSULUALINQ8",
    "external_id": "TRANSFER_001",
    "amount": 50000,
    "amount_xof": 50000,
    "service_name": "Wave Senegal",
    "destination_number": "772457199",
    "state": "pending",
    "fee_percent": 1,
    "created_at": "2024-03-28T02:08:40.000Z"
}
```

## États des transferts

Les transferts peuvent avoir plusieurs états :

| État | Description |
|------|-------------|
| `pending` | Transfert en cours de traitement |
| `processing` | Transfert en cours d'exécution |
| `success` | Transfert réussi |
| `failed` | Transfert échoué |
| `rejected` | Transfert rejeté |

## Notifications de transfert

PayTech envoie des notifications IPN pour informer du statut des transferts.

### Notification de succès

**Type d'événement** : `transfer_success`

```json
{
    "type_event": "transfer_success",
    "created_at": "2024-03-28T02:08:40.000Z",
    "external_id": "TRANSFER_001",
    "token_transfer": null,
    "id_transfer": "PYT-DEPOT-2QV4WGSULUALINQ8",
    "amount": 50000,
    "amount_xof": 50000,
    "service_items_id": "WAVE_SN_API_CASH_IN",
    "service_name": "Wave Senegal",
    "state": "success",
    "destination_number": "772457199",
    "validate_at": "2024-03-28T03:37:12.000Z",
    "failed_at": null,
    "fee_percent": 1,
    "rejected_at": null,
    "api_key_sha256": "4dad282f4bf6ecf4dcec3c70d9b5dacbde6fdf124230624712abec1c02b7e551",
    "api_secret_sha256": "b175778c90018c70c9b30993591b1ae0dd8f3d71ac2fb813de153d5e958ab0d3"
}
```

### Notification d'échec

**Type d'événement** : `transfer_failed`

```json
{
    "type_event": "transfer_failed",
    "created_at": "2024-03-28T02:08:40.000Z",
    "external_id": "TRANSFER_001",
    "token_transfer": null,
    "id_transfer": "PYT-DEPOT-2QV4WGSULUALINQ8",
    "amount": 50000,
    "amount_xof": 50000,
    "service_items_id": "WAVE_SN_API_CASH_IN",
    "service_name": "Wave Senegal",
    "state": "failed",
    "destination_number": "772457199",
    "validate_at": "2024-03-28T03:37:12.000Z",
    "failed_at": "2024-03-28T03:37:43.000Z",
    "fee_percent": 1,
    "rejected_at": "2024-03-28T03:37:43.000Z",
    "api_key_sha256": "4bf6ecf4dcec3fdf1242c7dacbde630624712abec1c02b7e5514dad282f0d9b5",
    "api_secret_sha256": "153d5edd8f3d71ac2fb8b1757958ab91b1ae078c90018c70c9b30993513de0d3"
}
```

## Traitement des notifications

### Exemple PHP

```php
<?php
function handleTransferNotification() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method not allowed');
    }
    
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data) {
        http_response_code(400);
        exit('Invalid JSON');
    }
    
    // Valider la signature (recommandé)
    if (!validateTransferSignature($data)) {
        http_response_code(400);
        exit('Invalid signature');
    }
    
    // Traiter selon le type d'événement
    switch ($data['type_event']) {
        case 'transfer_success':
            handleTransferSuccess($data);
            break;
            
        case 'transfer_failed':
            handleTransferFailure($data);
            break;
            
        default:
            error_log('Unknown transfer event: ' . $data['type_event']);
    }
    
    // Toujours répondre avec 200 OK
    http_response_code(200);
    echo 'OK';
}

function handleTransferSuccess($data) {
    // Mettre à jour le statut du transfert dans votre base de données
    $externalId = $data['external_id'];
    $transferId = $data['id_transfer'];
    $amount = $data['amount'];
    
    // Exemple de mise à jour
    updateTransferStatus($externalId, 'success', $transferId);
    
    // Notifier l'utilisateur du succès
    notifyUserTransferSuccess($externalId, $amount);
    
    error_log("Transfer success: {$externalId} - {$amount} XOF");
}

function handleTransferFailure($data) {
    // Mettre à jour le statut du transfert
    $externalId = $data['external_id'];
    $transferId = $data['id_transfer'];
    
    // Exemple de mise à jour
    updateTransferStatus($externalId, 'failed', $transferId);
    
    // Notifier l'utilisateur de l'échec
    notifyUserTransferFailure($externalId);
    
    error_log("Transfer failed: {$externalId}");
}

function validateTransferSignature($data) {
    // Implémenter la validation de signature
    // Similaire à la validation des paiements
    $expectedSignature = calculateTransferSignature($data);
    return hash_equals($expectedSignature, $data['signature'] ?? '');
}

// Traiter la notification
handleTransferNotification();
?>
```

### Exemple Node.js

```javascript
const express = require('express');
const crypto = require('crypto');
const app = express();

app.use(express.json());

app.post('/paytech/transfer-notification', (req, res) => {
    const data = req.body;
    
    // Valider la signature
    if (!validateTransferSignature(data)) {
        return res.status(400).send('Invalid signature');
    }
    
    // Traiter selon le type d'événement
    switch (data.type_event) {
        case 'transfer_success':
            handleTransferSuccess(data);
            break;
            
        case 'transfer_failed':
            handleTransferFailure(data);
            break;
            
        default:
            console.log('Unknown transfer event:', data.type_event);
    }
    
    res.status(200).send('OK');
});

function handleTransferSuccess(data) {
    console.log(`Transfer success: ${data.external_id} - ${data.amount} XOF`);
    
    // Mettre à jour votre base de données
    updateTransferStatus(data.external_id, 'success', data.id_transfer);
    
    // Notifier l'utilisateur
    notifyUserTransferSuccess(data.external_id, data.amount);
}

function handleTransferFailure(data) {
    console.log(`Transfer failed: ${data.external_id}`);
    
    // Mettre à jour votre base de données
    updateTransferStatus(data.external_id, 'failed', data.id_transfer);
    
    // Notifier l'utilisateur
    notifyUserTransferFailure(data.external_id);
}

function validateTransferSignature(data) {
    // Implémenter la validation de signature
    const expectedSignature = calculateTransferSignature(data);
    return crypto.timingSafeEqual(
        Buffer.from(expectedSignature, 'hex'),
        Buffer.from(data.signature || '', 'hex')
    );
}

app.listen(3000, () => {
    console.log('Transfer notification server running on port 3000');
});
```

## Gestion des erreurs

### Codes d'erreur courants

| Code | Description | Solution |
|------|-------------|----------|
| `400` | Paramètres invalides | Vérifier les données envoyées |
| `401` | Authentification échouée | Vérifier les clés API |
| `403` | Accès refusé | Vérifier les permissions du compte |
| `404` | Service non trouvé | Vérifier le `service_items_id` |
| `422` | Numéro invalide | Vérifier le format du numéro |
| `500` | Erreur serveur | Réessayer plus tard |

### Exemple de gestion d'erreur

```php
<?php
try {
    $result = requestTransfer($transferData);
    
    if ($result['success']) {
        echo "Transfert initié avec succès";
    } else {
        echo "Erreur: " . $result['message'];
    }
    
} catch (Exception $e) {
    // Logger l'erreur
    error_log("Transfer error: " . $e->getMessage());
    
    // Réponse utilisateur
    echo "Une erreur est survenue lors du transfert";
}
?>
```

## Bonnes pratiques

### Sécurité
- Toujours valider les signatures des notifications
- Utiliser HTTPS pour tous les endpoints
- Ne jamais exposer les clés API côté client
- Implémenter une authentification pour vos webhooks

### Fiabilité
- Implémenter un système de retry pour les échecs temporaires
- Stocker les `external_id` pour éviter les doublons
- Gérer les timeouts et les erreurs réseau
- Maintenir un log des transferts pour le debugging

### Performance
- Traiter les notifications de manière asynchrone
- Implémenter un système de queue pour les gros volumes
- Mettre en cache les informations de service
- Optimiser les requêtes de base de données

---

> 💡 **Conseil** : Testez toujours vos transferts en mode sandbox avant de passer en production. Assurez-vous que votre système peut gérer les notifications de succès et d'échec correctement.



## Notifications IPN pour les transferts

### ⚠️ Format des données IPN

> **🚨 IMPORTANT** : Les notifications IPN de transfert sont envoyées au format **POST URL-encoded**, **PAS en JSON**. Assurez-vous de traiter les données comme des paramètres de formulaire.

### Structure complète des données IPN de transfert

Votre endpoint IPN recevra une requête POST avec la structure suivante pour les transferts :

```json
{
  "type_event": "transfer_complete",
  "custom_field": "",
  "created_at": "2024-11-06 14:30:25",
  "external_id": "TRF_20241106_001",
  "callback_url": "https://votre-site.com/webhooks/paytech/transfer",
  "token_transfer": "4fe7bb6bedbd94689e89",
  "id_transfer": "TXN_123456789",
  "amount": 5000,
  "amount_xof": 5000,
  "service_items_id": "WAVE_SN_API_CASH_IN",
  "service_name": "Wave Sénégal",
  "state": "success",
  "destination_number": "221771234567",
  "validate_at": "2024-11-06 14:31:15",
  "failed_at": null,
  "fee_percent": 1.0,
  "rejected_at": null,
  "api_key_sha256": "dacbde6382f4bf6ecf4dcec0624712abec1c02b7e5514dad23fdf1242c70d9b5",
  "api_secret_sha256": "91b1ae073d5edd8f3d71ac2fb88c90018c70c9b30993513de15b1757958ab0d3",
  "hmac_compute": "a8b2c3d4e5f6789012345678901234567890abcdef1234567890abcdef123456"
}
```

### Description des champs spécifiques aux transferts

| Champ | Type | Description |
|-------|------|-------------|
| `type_event` | string | Type d'événement : "transfer_complete" |
| `external_id` | string | Identifiant unique de votre système |
| `token_transfer` | string | Token unique du transfert |
| `id_transfer` | string | ID de transaction PayTech |
| `amount` | number | Montant du transfert |
| `amount_xof` | number | Montant en XOF |
| `service_items_id` | string | ID du service utilisé |
| `service_name` | string | Nom du service (Wave, Orange Money, etc.) |
| `state` | string | Statut : "success", "failed", "pending" |
| `destination_number` | string | Numéro de téléphone du bénéficiaire |
| `validate_at` | string | Date de validation (si succès) |
| `failed_at` | string | Date d'échec (si échec) |
| `fee_percent` | number | Pourcentage de frais appliqué |
| `rejected_at` | string | Date de rejet (si rejeté) |

## Validation des notifications IPN de transfert

PayTech propose **deux méthodes de validation** pour les transferts :

### Méthode 1 : Validation HMAC (Recommandée)

La signature HMAC pour les transferts utilise la formule :
```
message = amount|id_transfer|api_key
signature = HMAC-SHA256(message, api_secret)
```

#### Implémentation PHP pour transferts

```php
<?php
function generateHMACSHA256($message, $secretKey) {
    try {
        return hash_hmac('sha256', $message, $secretKey);
    } catch (Exception $e) {
        error_log("HMAC Error: " . $e->getMessage());
        return false;
    }
}

function validateTransferIPNWithHMAC($requestData, $apiKey, $apiSecret) {
    // Récupération des données nécessaires
    $amount = $requestData['amount'];
    $idTransfer = $requestData['id_transfer'];
    $receivedHmac = $requestData['hmac_compute'];
    
    if (!$amount || !$idTransfer || !$receivedHmac) {
        return false;
    }
    
    // Construction du message pour HMAC
    $message = $amount . '|' . $idTransfer . '|' . $apiKey;
    
    // Génération de la signature attendue
    $expectedHmac = generateHMACSHA256($message, $apiSecret);
    
    // Comparaison sécurisée
    return hash_equals($expectedHmac, $receivedHmac);
}
?>
```

#### Implémentation Node.js pour transferts

```javascript
const crypto = require('crypto');

function generateHMACSHA256(message, secretKey) {
    try {
        const hmac = crypto.createHmac('sha256', secretKey);
        hmac.update(message);
        return hmac.digest('hex');
    } catch (e) {
        console.log(e);
        return e.message;
    }
}

function validateTransferIPNWithHMAC(requestData, apiKey, apiSecret) {
    const { amount, id_transfer, hmac_compute } = requestData;
    
    if (!amount || !id_transfer || !hmac_compute) {
        return false;
    }
    
    // Construction du message
    const message = `${amount}|${id_transfer}|${apiKey}`;
    
    // Génération de la signature attendue
    const expectedHmac = generateHMACSHA256(message, apiSecret);
    
    // Comparaison sécurisée
    return crypto.timingSafeEqual(
        Buffer.from(expectedHmac, 'hex'),
        Buffer.from(hmac_compute, 'hex')
    );
}
```

#### Implémentation Python pour transferts

```python
import hmac
import hashlib

def generate_hmac_sha256(message, secret_key):
    try:
        return hmac.new(
            secret_key.encode('utf-8'),
            message.encode('utf-8'),
            hashlib.sha256
        ).hexdigest()
    except Exception as e:
        print(f"HMAC Error: {e}")
        return None

def validate_transfer_ipn_with_hmac(request_data, api_key, api_secret):
    amount = request_data.get('amount')
    id_transfer = request_data.get('id_transfer')
    received_hmac = request_data.get('hmac_compute')
    
    if not all([amount, id_transfer, received_hmac]):
        return False
    
    # Construction du message
    message = f"{amount}|{id_transfer}|{api_key}"
    
    # Génération de la signature attendue
    expected_hmac = generate_hmac_sha256(message, api_secret)
    
    # Comparaison sécurisée
    return hmac.compare_digest(expected_hmac, received_hmac)
```

### Méthode 2 : Validation SHA256 (Alternative)

Si vous préférez utiliser la validation par hash SHA256 :

```php
<?php
function validateTransferIPNWithSHA256($requestData, $apiKey, $apiSecret) {
    $receivedApiKeyHash = $requestData['api_key_sha256'] ?? '';
    $receivedSecretHash = $requestData['api_secret_sha256'] ?? '';
    
    $expectedApiKeyHash = hash('sha256', $apiKey);
    $expectedSecretHash = hash('sha256', $apiSecret);
    
    return ($receivedApiKeyHash === $expectedApiKeyHash && 
            $receivedSecretHash === $expectedSecretHash);
}
?>
```

### Validation flexible pour transferts

```php
<?php
function validateTransferIPN($requestData, $apiKey, $apiSecret) {
    // Priorité à la validation HMAC si disponible
    if (isset($requestData['hmac_compute']) && !empty($requestData['hmac_compute'])) {
        $isValid = validateTransferIPNWithHMAC($requestData, $apiKey, $apiSecret);
        error_log('Transfer HMAC validation: ' . ($isValid ? 'VALID' : 'INVALID'));
        return $isValid;
    }
    
    // Fallback sur la validation SHA256
    if (isset($requestData['api_key_sha256']) && isset($requestData['api_secret_sha256'])) {
        $isValid = validateTransferIPNWithSHA256($requestData, $apiKey, $apiSecret);
        error_log('Transfer SHA256 validation: ' . ($isValid ? 'VALID' : 'INVALID'));
        return $isValid;
    }
    
    error_log('No validation method available for transfer IPN');
    return false;
}
?>
```

## Endpoint IPN complet pour transferts

```php
<?php
// webhook_transfer.php - Endpoint IPN PayTech pour transferts

// Configuration
define('PAYTECH_API_KEY', 'votre_api_key');
define('PAYTECH_API_SECRET', 'votre_api_secret');

// Désactiver la vérification CSRF pour cette route
if (strpos($_SERVER['REQUEST_URI'], '/webhooks/paytech/transfer') !== false) {
    // Désactiver CSRF selon votre framework
}

// Fonction de logging spécifique aux transferts
function logTransferIPN($message, $data = null) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] TRANSFER IPN: $message";
    if ($data) {
        $logMessage .= " - Data: " . json_encode($data);
    }
    error_log($logMessage . "\n", 3, '/var/log/paytech_transfer_ipn.log');
}

try {
    // Récupération des données POST URL-encoded
    $data = $_POST; // Pour les données URL-encoded
    
    if (empty($data)) {
        // Fallback pour JSON si nécessaire
        $input = file_get_contents('php://input');
        $data = json_decode($input, true) ?: [];
    }
    
    if (empty($data)) {
        logTransferIPN('Error: No data received');
        http_response_code(400);
        exit('No data received');
    }
    
    logTransferIPN('Received', $data);
    
    // Validation de l'authenticité
    if (!validateTransferIPN($data, PAYTECH_API_KEY, PAYTECH_API_SECRET)) {
        logTransferIPN('Error: Invalid authentication');
        http_response_code(403);
        exit('IPN KO NOT FROM PAYTECH');
    }
    
    // Vérification du type d'événement
    if ($data['type_event'] !== 'transfer_complete') {
        logTransferIPN('Error: Unknown event type', $data['type_event']);
        http_response_code(400);
        exit('Unknown event type');
    }
    
    // Extraction des données importantes
    $externalId = $data['external_id'];
    $amount = $data['amount'];
    $state = $data['state'];
    $serviceName = $data['service_name'];
    $destinationNumber = $data['destination_number'];
    $idTransfer = $data['id_transfer'];
    
    // Traitement selon le statut
    $success = false;
    switch ($state) {
        case 'success':
            $success = processTransferSuccess($externalId, $amount, $serviceName, $destinationNumber, $data);
            break;
        case 'failed':
            $success = processTransferFailure($externalId, $data['failed_at'], $data);
            break;
        case 'pending':
            $success = processTransferPending($externalId, $data);
            break;
        default:
            logTransferIPN('Error: Unknown transfer state', $state);
            http_response_code(400);
            exit('Unknown transfer state');
    }
    
    if ($success) {
        logTransferIPN('Success: Transfer processed', [
            'external_id' => $externalId,
            'state' => $state,
            'id_transfer' => $idTransfer
        ]);
        echo 'IPN OK';
    } else {
        logTransferIPN('Error: Transfer processing failed', $externalId);
        http_response_code(500);
        exit('Processing failed');
    }
    
} catch (Exception $e) {
    logTransferIPN('Exception: ' . $e->getMessage());
    http_response_code(500);
    exit('Internal error');
}

function processTransferSuccess($externalId, $amount, $serviceName, $destinationNumber, $fullData) {
    try {
        // Connexion à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=votre_db', 'user', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Vérification que le transfert existe
        $stmt = $pdo->prepare("SELECT id, status, amount FROM transfers WHERE external_id = ?");
        $stmt->execute([$externalId]);
        $transfer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$transfer) {
            logTransferIPN('Transfer not found', $externalId);
            return false;
        }
        
        if ($transfer['status'] === 'completed') {
            logTransferIPN('Transfer already completed', $externalId);
            return true; // Déjà traité
        }
        
        // Vérification du montant
        if ($transfer['amount'] != $amount) {
            logTransferIPN('Amount mismatch', [
                'expected' => $transfer['amount'],
                'received' => $amount
            ]);
            return false;
        }
        
        // Mise à jour du statut
        $stmt = $pdo->prepare("
            UPDATE transfers 
            SET status = 'completed',
                service_name = ?,
                destination_number = ?,
                completed_at = NOW(),
                paytech_data = ?
            WHERE external_id = ?
        ");
        
        $paytechData = json_encode([
            'id_transfer' => $fullData['id_transfer'],
            'token_transfer' => $fullData['token_transfer'],
            'validate_at' => $fullData['validate_at'],
            'fee_percent' => $fullData['fee_percent'],
            'service_items_id' => $fullData['service_items_id']
        ]);
        
        $stmt->execute([$serviceName, $destinationNumber, $paytechData, $externalId]);
        
        // Actions post-transfert (notifications, etc.)
        triggerPostTransferActions($transfer['id'], 'success');
        
        return true;
        
    } catch (PDOException $e) {
        logTransferIPN('Database error: ' . $e->getMessage());
        return false;
    }
}

function processTransferFailure($externalId, $failedAt, $fullData) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=votre_db', 'user', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("
            UPDATE transfers 
            SET status = 'failed',
                failed_at = ?,
                paytech_data = ?
            WHERE external_id = ?
        ");
        
        $paytechData = json_encode([
            'id_transfer' => $fullData['id_transfer'],
            'failed_at' => $failedAt,
            'reason' => 'Transfer failed on PayTech side'
        ]);
        
        $stmt->execute([$failedAt, $paytechData, $externalId]);
        
        // Actions post-échec (notifications, remboursement, etc.)
        triggerPostTransferActions($externalId, 'failed');
        
        return true;
        
    } catch (PDOException $e) {
        logTransferIPN('Database error: ' . $e->getMessage());
        return false;
    }
}

function processTransferPending($externalId, $fullData) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=votre_db', 'user', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("
            UPDATE transfers 
            SET status = 'pending',
                paytech_data = ?
            WHERE external_id = ?
        ");
        
        $paytechData = json_encode([
            'id_transfer' => $fullData['id_transfer'],
            'updated_at' => date('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);
        
        $stmt->execute([$paytechData, $externalId]);
        
        return true;
        
    } catch (PDOException $e) {
        logTransferIPN('Database error: ' . $e->getMessage());
        return false;
    }
}

function triggerPostTransferActions($transferId, $status) {
    // Envoi de notifications
    // Mise à jour des soldes
    // Logging des métriques
    // etc.
}
?>
```

## Bonnes pratiques pour les IPN de transfert

### Sécurité

1. **Toujours valider** l'authenticité avec HMAC ou SHA256
2. **Utiliser HTTPS** pour toutes les URLs IPN
3. **Désactiver CSRF** pour les endpoints IPN
4. **Logger toutes** les notifications reçues
5. **Vérifier les montants** avant traitement

### Performance

1. **Traitement asynchrone** pour les actions lourdes
2. **Réponse rapide** ("IPN OK") à PayTech
3. **Gestion des doublons** avec external_id
4. **Timeout approprié** pour les requêtes base de données

### Monitoring

1. **Alertes** sur les échecs de validation
2. **Métriques** sur les taux de succès/échec
3. **Logs détaillés** pour le débogage
4. **Surveillance** des temps de réponse

Le système de transfert PayTech avec notifications IPN offre une solution complète pour gérer les transferts d'argent avec une validation sécurisée et un suivi en temps réel des statuts.


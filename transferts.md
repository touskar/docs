# Transferts PayTech

PayTech propose un système de transfert d'argent qui permet d'envoyer des fonds directement vers les comptes de mobile money des bénéficiaires. Cette section détaille le fonctionnement des notifications IPN pour les transferts.

## Vue d'ensemble

Le système de transfert PayTech permet de :

- **Recevoir des notifications IPN** en temps réel sur le statut des transferts
- **Gérer les succès et échecs** de transfert
- **Suivre les frais** et commissions appliqués
- **Valider l'authenticité** des notifications avec HMAC ou SHA256

> **⚠️ Important** : PayTech ne fournit pas d'API pour initier des transferts. Les transferts sont initiés depuis le dashboard PayTech et vous recevez uniquement les notifications IPN pour suivre leur statut.

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

## Notifications IPN pour les transferts

### ⚠️ Format des données IPN

> **🚨 IMPORTANT** : Les notifications IPN de transfert sont envoyées au format **POST URL-encoded**, **PAS en JSON**. Assurez-vous de traiter les données comme des paramètres de formulaire.

### Types d'événements de transfert

PayTech envoie deux types de notifications IPN pour les transferts :

1. **`transfer_success`** - Transfert réussi
2. **`transfer_failed`** - Transfert échoué

### Structure des données IPN - Transfert réussi

```json
{
    "type_event": "transfer_success",
    "created_at": "2024-03-28T02:08:40.000Z",
    "external_id": "UALI2QV4WGSULNQ8",
    "token_transfer": null,
    "id_transfer": "PYT-DEPOT-2QV4WGSULUALINQ8",
    "amount": 500,
    "amount_xof": 500,
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

### Structure des données IPN - Transfert échoué

```json
{
    "type_event": "transfer_failed",
    "created_at": "2024-03-28T02:08:40.000Z",
    "external_id": "UALI2QV4WGSULNQ8",
    "token_transfer": null,
    "id_transfer": "PYT-DEPOT-2QV4WGSULUALINQ8",
    "amount": 500,
    "amount_xof": 500,
    "service_items_id": "WAVE_SN_API_CASH_IN",
    "service_name": "Wave Senegal",
    "state": "failed",
    "destination_number": "772457199",
    "validate_at": null,
    "failed_at": "2024-03-28T03:37:43.000Z",
    "fee_percent": 1,
    "rejected_at": "2024-03-28T03:37:43.000Z",
    "api_key_sha256": "4bf6ecf4dcec3fdf1242c7dacbde630624712abec1c02b7e5514dad282f0d9b5",
    "api_secret_sha256": "153d5edd8f3d71ac2fb8b1757958ab91b1ae078c90018c70c9b30993513de0d3"
}
```

### Description des champs spécifiques aux transferts

| Champ | Type | Description |
|-------|------|-------------|
| `type_event` | string | Type d'événement : "transfer_success" ou "transfer_failed" |
| `created_at` | string | Date de création du transfert (ISO 8601) |
| `external_id` | string | Identifiant externe du transfert |
| `token_transfer` | string/null | Token du transfert (peut être null) |
| `id_transfer` | string | ID unique du transfert PayTech |
| `amount` | number | Montant du transfert |
| `amount_xof` | number | Montant en XOF |
| `service_items_id` | string | ID du service utilisé (ex: WAVE_SN_API_CASH_IN) |
| `service_name` | string | Nom du service (ex: Wave Senegal) |
| `state` | string | Statut : "success" ou "failed" |
| `destination_number` | string | Numéro de téléphone du bénéficiaire |
| `validate_at` | string/null | Date de validation (si succès) |
| `failed_at` | string/null | Date d'échec (si échec) |
| `fee_percent` | number | Pourcentage de frais appliqué |
| `rejected_at` | string/null | Date de rejet (si rejeté) |
| `api_key_sha256` | string | Hash SHA256 de votre API_KEY |
| `api_secret_sha256` | string | Hash SHA256 de votre API_SECRET |

## Validation des notifications IPN de transfert

PayTech propose **deux méthodes de validation** pour les transferts :

### Méthode 1 : Validation SHA256 (Standard)

Pour garantir que la notification provient bien de PayTech, validez les hash SHA256 des clés API :

```php
<?php
function validateTransferIPNFromPayTech($requestData, $apiKey, $apiSecret) {
    $receivedApiKeyHash = $requestData['api_key_sha256'];
    $receivedSecretHash = $requestData['api_secret_sha256'];
    
    $expectedApiKeyHash = hash('sha256', $apiKey);
    $expectedSecretHash = hash('sha256', $apiSecret);
    
    return ($receivedApiKeyHash === $expectedApiKeyHash && 
            $receivedSecretHash === $expectedSecretHash);
}
?>
```

### Méthode 2 : Validation HMAC (Si disponible)

Si le champ `hmac_compute` est présent, utilisez la validation HMAC :

```php
<?php
function validateTransferIPNWithHMAC($requestData, $apiKey, $apiSecret) {
    if (!isset($requestData['hmac_compute'])) {
        return false;
    }
    
    $amount = $requestData['amount'];
    $idTransfer = $requestData['id_transfer'];
    $receivedHmac = $requestData['hmac_compute'];
    
    // Construction du message pour HMAC
    $message = $amount . '|' . $idTransfer . '|' . $apiKey;
    
    // Génération de la signature attendue
    $expectedHmac = hash_hmac('sha256', $message, $apiSecret);
    
    // Comparaison sécurisée
    return hash_equals($expectedHmac, $receivedHmac);
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
        $isValid = validateTransferIPNFromPayTech($requestData, $apiKey, $apiSecret);
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
    if (!in_array($data['type_event'], ['transfer_success', 'transfer_failed'])) {
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
    $typeEvent = $data['type_event'];
    
    // Traitement selon le type d'événement
    $success = false;
    switch ($typeEvent) {
        case 'transfer_success':
            $success = processTransferSuccess($externalId, $amount, $serviceName, $destinationNumber, $data);
            break;
        case 'transfer_failed':
            $success = processTransferFailure($externalId, $data['failed_at'], $data);
            break;
        default:
            logTransferIPN('Error: Unknown transfer event type', $typeEvent);
            http_response_code(400);
            exit('Unknown transfer event type');
    }
    
    if ($success) {
        logTransferIPN('Success: Transfer processed', [
            'external_id' => $externalId,
            'type_event' => $typeEvent,
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
                completed_at = ?,
                paytech_data = ?
            WHERE external_id = ?
        ");
        
        $paytechData = json_encode([
            'id_transfer' => $fullData['id_transfer'],
            'token_transfer' => $fullData['token_transfer'],
            'validate_at' => $fullData['validate_at'],
            'fee_percent' => $fullData['fee_percent'],
            'service_items_id' => $fullData['service_items_id'],
            'type_event' => $fullData['type_event']
        ]);
        
        $stmt->execute([
            $serviceName, 
            $destinationNumber, 
            $fullData['validate_at'], 
            $paytechData, 
            $externalId
        ]);
        
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
                rejected_at = ?,
                paytech_data = ?
            WHERE external_id = ?
        ");
        
        $paytechData = json_encode([
            'id_transfer' => $fullData['id_transfer'],
            'failed_at' => $failedAt,
            'rejected_at' => $fullData['rejected_at'],
            'type_event' => $fullData['type_event'],
            'reason' => 'Transfer failed on PayTech side'
        ]);
        
        $stmt->execute([
            $failedAt, 
            $fullData['rejected_at'], 
            $paytechData, 
            $externalId
        ]);
        
        // Actions post-échec (notifications, remboursement, etc.)
        triggerPostTransferActions($externalId, 'failed');
        
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

## Exemples d'intégration

### Node.js Express

```javascript
const express = require('express');
const crypto = require('crypto');

const app = express();
app.use(express.urlencoded({ extended: true })); // Pour POST URL-encoded

// Configuration
const PAYTECH_API_KEY = process.env.PAYTECH_API_KEY;
const PAYTECH_API_SECRET = process.env.PAYTECH_API_SECRET;

// Validation SHA256
function validateTransferIPN(data) {
    const receivedApiKeyHash = data.api_key_sha256;
    const receivedSecretHash = data.api_secret_sha256;
    
    const expectedApiKeyHash = crypto.createHash('sha256')
        .update(PAYTECH_API_KEY)
        .digest('hex');
    const expectedSecretHash = crypto.createHash('sha256')
        .update(PAYTECH_API_SECRET)
        .digest('hex');
    
    return (receivedApiKeyHash === expectedApiKeyHash && 
            receivedSecretHash === expectedSecretHash);
}

// Endpoint IPN transferts
app.post('/webhooks/paytech/transfer', (req, res) => {
    try {
        const data = req.body;
        
        console.log('Transfer IPN Received:', JSON.stringify(data, null, 2));
        
        // Validation de l'authenticité
        if (!validateTransferIPN(data)) {
            console.error('Transfer IPN Error: Invalid authentication');
            return res.status(403).send('IPN KO NOT FROM PAYTECH');
        }
        
        // Vérification du type d'événement
        if (!['transfer_success', 'transfer_failed'].includes(data.type_event)) {
            console.error('Transfer IPN Error: Unknown event type', data.type_event);
            return res.status(400).send('Unknown event type');
        }
        
        // Traitement selon le type
        if (data.type_event === 'transfer_success') {
            processTransferSuccess(data);
        } else if (data.type_event === 'transfer_failed') {
            processTransferFailure(data);
        }
        
        console.log('Transfer IPN processed successfully');
        res.status(200).send('IPN OK');
        
    } catch (error) {
        console.error('Transfer IPN Error:', error.message);
        res.status(500).send('Internal error');
    }
});

function processTransferSuccess(data) {
    console.log(`Transfer ${data.external_id} completed successfully`);
    console.log(`Amount: ${data.amount} XOF to ${data.destination_number}`);
    console.log(`Service: ${data.service_name}`);
    
    // Votre logique métier ici
    // - Mettre à jour la base de données
    // - Envoyer des notifications
    // - Déclencher des webhooks
}

function processTransferFailure(data) {
    console.log(`Transfer ${data.external_id} failed`);
    console.log(`Failed at: ${data.failed_at}`);
    console.log(`Rejected at: ${data.rejected_at}`);
    
    // Votre logique métier ici
    // - Mettre à jour la base de données
    // - Notifier l'utilisateur
    // - Gérer les remboursements
}

app.listen(3000, () => {
    console.log('Transfer IPN server listening on port 3000');
});
```

### Python Django

```python
import json
import hashlib
import hmac
from django.http import HttpResponse
from django.views.decorators.csrf import csrf_exempt
from django.views.decorators.http import require_http_methods
from django.conf import settings
import logging

logger = logging.getLogger(__name__)

def validate_transfer_ipn(data):
    """Valide l'authenticité d'une notification IPN de transfert"""
    received_api_key_hash = data.get('api_key_sha256', '')
    received_secret_hash = data.get('api_secret_sha256', '')
    
    expected_api_key_hash = hashlib.sha256(
        settings.PAYTECH_API_KEY.encode()
    ).hexdigest()
    expected_secret_hash = hashlib.sha256(
        settings.PAYTECH_SECRET_KEY.encode()
    ).hexdigest()
    
    return (received_api_key_hash == expected_api_key_hash and 
            received_secret_hash == expected_secret_hash)

@csrf_exempt
@require_http_methods(["POST"])
def transfer_ipn_webhook(request):
    """Endpoint pour recevoir les notifications IPN de transfert"""
    try:
        # Récupération des données POST
        data = request.POST.dict()
        
        logger.info(f'Transfer IPN Received: {json.dumps(data, indent=2)}')
        
        # Validation de l'authenticité
        if not validate_transfer_ipn(data):
            logger.error('Transfer IPN Error: Invalid authentication')
            return HttpResponse('IPN KO NOT FROM PAYTECH', status=403)
        
        # Vérification du type d'événement
        type_event = data.get('type_event')
        if type_event not in ['transfer_success', 'transfer_failed']:
            logger.error(f'Transfer IPN Error: Unknown event type {type_event}')
            return HttpResponse('Unknown event type', status=400)
        
        # Traitement selon le type
        if type_event == 'transfer_success':
            process_transfer_success(data)
        elif type_event == 'transfer_failed':
            process_transfer_failure(data)
        
        logger.info('Transfer IPN processed successfully')
        return HttpResponse('IPN OK', status=200)
        
    except Exception as e:
        logger.error(f'Transfer IPN Error: {str(e)}')
        return HttpResponse('Internal error', status=500)

def process_transfer_success(data):
    """Traite un transfert réussi"""
    external_id = data.get('external_id')
    amount = data.get('amount')
    destination_number = data.get('destination_number')
    service_name = data.get('service_name')
    
    logger.info(f'Transfer {external_id} completed successfully')
    logger.info(f'Amount: {amount} XOF to {destination_number}')
    logger.info(f'Service: {service_name}')
    
    # Votre logique métier ici
    # - Mettre à jour le modèle Transfer
    # - Envoyer des notifications
    # - Déclencher des tâches asynchrones

def process_transfer_failure(data):
    """Traite un transfert échoué"""
    external_id = data.get('external_id')
    failed_at = data.get('failed_at')
    rejected_at = data.get('rejected_at')
    
    logger.info(f'Transfer {external_id} failed')
    logger.info(f'Failed at: {failed_at}')
    logger.info(f'Rejected at: {rejected_at}')
    
    # Votre logique métier ici
    # - Mettre à jour le statut
    # - Notifier l'utilisateur
    # - Gérer les remboursements
```

## Bonnes pratiques pour les IPN de transfert

### Sécurité

1. **Toujours valider** l'authenticité avec SHA256 ou HMAC
2. **Utiliser HTTPS** pour toutes les URLs IPN
3. **Désactiver CSRF** pour les endpoints IPN
4. **Logger toutes** les notifications reçues
5. **Vérifier les montants** et identifiants

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

### Gestion des erreurs

1. **Retry automatique** pour les échecs temporaires
2. **Dead letter queue** pour les échecs persistants
3. **Notification admin** sur les erreurs critiques
4. **Fallback** sur validation alternative

Le système de transfert PayTech avec notifications IPN offre une solution complète pour suivre les transferts d'argent avec une validation sécurisée et un monitoring en temps réel des statuts.


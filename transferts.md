# Transferts PayTech

PayTech propose un système de transfert d'argent qui permet d'envoyer des fonds directement vers les comptes de mobile money des bénéficiaires. Cette section détaille l'utilisation de l'API de transfert et la gestion des notifications.

## Vue d'ensemble

Le système de transfert PayTech permet de :

- **Envoyer de l'argent** vers les comptes Orange Money, Wave, Tigo Cash, etc.
- **Recevoir des notifications** en temps réel sur le statut des transferts
- **Gérer les échecs** et les tentatives de transfert
- **Suivre les frais** et commissions appliqués

## Services de transfert supportés

### Wave Sénégal
- **Service ID** : `WAVE_SN_API_CASH_IN`
- **Devise** : XOF (Franc CFA)
- **Frais** : 1% du montant transféré
- **Numéros supportés** : 77xxx xxxx, 78xxx xxxx

### Orange Money
- **Service ID** : `ORANGE_MONEY_SN_API_CASH_IN`
- **Devise** : XOF (Franc CFA)
- **Frais** : Variables selon le montant
- **Numéros supportés** : 77xxx xxxx, 78xxx xxxx

### Tigo Cash
- **Service ID** : `TIGO_CASH_SN_API_CASH_IN`
- **Devise** : XOF (Franc CFA)
- **Frais** : Variables selon le montant
- **Numéros supportés** : 76xxx xxxx

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


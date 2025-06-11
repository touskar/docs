# PayTech Transfers

PayTech offers a money transfer system that allows sending funds directly to mobile money accounts of beneficiaries. This section details the use of the transfer API and notification management.

## Overview

The PayTech transfer system allows you to:

- **Send money** to Orange Money, Wave, Tigo Cash accounts, etc.
- **Receive real-time notifications** on transfer status
- **Handle failures** and transfer attempts
- **Track fees** and commissions applied

## Supported Transfer Services

### Wave Senegal
- **Service ID**: `WAVE_SN_API_CASH_IN`
- **Currency**: XOF (CFA Franc)
- **Fees**: 1% of transferred amount
- **Supported numbers**: 77xxx xxxx, 78xxx xxxx

### Orange Money
- **Service ID**: `ORANGE_MONEY_SN_API_CASH_IN`
- **Currency**: XOF (CFA Franc)
- **Fees**: Variable based on amount
- **Supported numbers**: 77xxx xxxx, 78xxx xxxx

### Tigo Cash
- **Service ID**: `TIGO_CASH_SN_API_CASH_IN`
- **Currency**: XOF (CFA Franc)
- **Fees**: Variable based on amount
- **Supported numbers**: 76xxx xxxx

## Transfer Request

### Endpoint
```
POST https://paytech.sn/api/transfer/request
```

### Required Headers
```http
Content-Type: application/json
API-KEY: your_api_key
API-SECRET: your_api_secret
```

### Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `external_id` | string | Yes | Unique identifier from your system |
| `amount` | integer | Yes | Amount in XOF (cents) |
| `service_items_id` | string | Yes | Transfer service ID |
| `destination_number` | string | Yes | Beneficiary number |
| `env` | string | Yes | Environment (`test` or `prod`) |

### Request Example

```bash
curl -X POST https://paytech.sn/api/transfer/request \
  -H "Content-Type: application/json" \
  -H "API-KEY: your_api_key" \
  -H "API-SECRET: your_api_secret" \
  -d '{
    "external_id": "TRANSFER_001",
    "amount": 50000,
    "service_items_id": "WAVE_SN_API_CASH_IN",
    "destination_number": "772457199",
    "env": "test"
  }'
```

### PHP Example

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

// Usage example
$transferData = [
    'external_id' => 'TRANSFER_' . time(),
    'amount' => 50000, // 500 XOF
    'service_items_id' => 'WAVE_SN_API_CASH_IN',
    'destination_number' => '772457199',
    'env' => 'test'
];

try {
    $result = requestTransfer($transferData);
    echo "Transfer initiated: " . $result['id_transfer'];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

### Success Response

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

## Transfer States

Transfers can have several states:

| State | Description |
|-------|-------------|
| `pending` | Transfer being processed |
| `processing` | Transfer in execution |
| `success` | Transfer successful |
| `failed` | Transfer failed |
| `rejected` | Transfer rejected |

## Transfer Notifications

PayTech sends IPN notifications to inform about transfer status.

### Success Notification

**Event Type**: `transfer_success`

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

### Failure Notification

**Event Type**: `transfer_failed`

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

## Processing Notifications

### PHP Example

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
    
    // Validate signature (recommended)
    if (!validateTransferSignature($data)) {
        http_response_code(400);
        exit('Invalid signature');
    }
    
    // Process based on event type
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
    
    // Always respond with 200 OK
    http_response_code(200);
    echo 'OK';
}

function handleTransferSuccess($data) {
    // Update transfer status in your database
    $externalId = $data['external_id'];
    $transferId = $data['id_transfer'];
    $amount = $data['amount'];
    
    // Example update
    updateTransferStatus($externalId, 'success', $transferId);
    
    // Notify user of success
    notifyUserTransferSuccess($externalId, $amount);
    
    error_log("Transfer success: {$externalId} - {$amount} XOF");
}

function handleTransferFailure($data) {
    // Update transfer status
    $externalId = $data['external_id'];
    $transferId = $data['id_transfer'];
    
    // Example update
    updateTransferStatus($externalId, 'failed', $transferId);
    
    // Notify user of failure
    notifyUserTransferFailure($externalId);
    
    error_log("Transfer failed: {$externalId}");
}

// Process notification
handleTransferNotification();
?>
```

### Node.js Example

```javascript
const express = require('express');
const crypto = require('crypto');
const app = express();

app.use(express.json());

app.post('/paytech/transfer-notification', (req, res) => {
    const data = req.body;
    
    // Validate signature
    if (!validateTransferSignature(data)) {
        return res.status(400).send('Invalid signature');
    }
    
    // Process based on event type
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
    
    // Update your database
    updateTransferStatus(data.external_id, 'success', data.id_transfer);
    
    // Notify user
    notifyUserTransferSuccess(data.external_id, data.amount);
}

function handleTransferFailure(data) {
    console.log(`Transfer failed: ${data.external_id}`);
    
    // Update your database
    updateTransferStatus(data.external_id, 'failed', data.id_transfer);
    
    // Notify user
    notifyUserTransferFailure(data.external_id);
}

app.listen(3000, () => {
    console.log('Transfer notification server running on port 3000');
});
```

## Error Handling

### Common Error Codes

| Code | Description | Solution |
|------|-------------|----------|
| `400` | Invalid parameters | Check sent data |
| `401` | Authentication failed | Check API keys |
| `403` | Access denied | Check account permissions |
| `404` | Service not found | Check `service_items_id` |
| `422` | Invalid number | Check number format |
| `500` | Server error | Retry later |

## Best Practices

### Security
- Always validate notification signatures
- Use HTTPS for all endpoints
- Never expose API keys client-side
- Implement authentication for your webhooks

### Reliability
- Implement retry system for temporary failures
- Store `external_id` to avoid duplicates
- Handle timeouts and network errors
- Maintain transfer logs for debugging

### Performance
- Process notifications asynchronously
- Implement queue system for high volumes
- Cache service information
- Optimize database queries

---

> 💡 **Tip**: Always test your transfers in sandbox mode before going to production. Make sure your system can handle both success and failure notifications correctly.


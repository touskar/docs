# Demande de paiement

La demande de paiement est l'étape fondamentale pour initier une transaction avec PayTech. Cette section détaille le processus complet, de la création de la demande à la gestion de la réponse.

## Vue d'ensemble

La demande de paiement définit une expérience utilisateur cohérente entre les méthodes de paiement, les systèmes de paiement, les plateformes et les commerçants. Pour qu'un client puisse effectuer un paiement, le site marchand doit d'abord adresser une demande de paiement à l'API PayTech, qui retournera un token de 20 octets servant d'identifiant unique pour la transaction.

## Endpoint de demande

```http
POST /payment/request-payment
```

**URL complète** : `https://paytech.sn/api/payment/request-payment`

## Paramètres de la requête

### Paramètres obligatoires

| Paramètre | Type | Description |
|-----------|------|-------------|
| `item_name` | string | Nom du produit ou service |
| `item_price` | number | Prix de la commande (en centimes pour XOF, ou unité de base pour autres devises) |
| `ref_command` | string | Référence unique de commande générée par le site marchand |
| `command_name` | string | Description détaillée de la commande |

### Paramètres optionnels

| Paramètre | Type | Défaut | Description |
|-----------|------|--------|-------------|
| `currency` | string | "XOF" | Code devise : XOF, EUR, USD, CAD, GBP, MAD |
| `env` | string | "prod" | Environnement : "test" ou "prod" |
| `ipn_url` | string | Paramètre global | URL de notification IPN (HTTPS uniquement) |
| `success_url` | string | Paramètre global | URL de redirection après paiement réussi |
| `cancel_url` | string | Paramètre global | URL de redirection après annulation |
| `custom_field` | string | null | Données additionnelles (JSON encodé) |

## Exemple de requête

### Requête basique

```json
{
  "item_name": "iPhone 13 Pro",
  "item_price": 650000,
  "currency": "XOF",
  "ref_command": "CMD_20241106_001",
  "command_name": "Achat iPhone 13 Pro 256GB Bleu Alpin",
  "env": "test"
}
```

### Requête complète avec URLs personnalisées

```json
{
  "item_name": "Abonnement Premium",
  "item_price": 29.99,
  "currency": "EUR",
  "ref_command": "SUB_20241106_002",
  "command_name": "Abonnement Premium - 1 mois",
  "env": "prod",
  "success_url": "https://monsite.com/payment/success",
  "cancel_url": "https://monsite.com/payment/cancel",
  "ipn_url": "https://monsite.com/webhooks/paytech",
  "custom_field": "{\"user_id\":12345,\"plan\":\"premium\",\"duration\":\"1month\"}"
}
```

## Réponse de l'API

### Réponse de succès

```json
{
  "success": 1,
  "token": "4fe7bb6bedbd94689e89",
  "redirect_url": "https://paytech.sn/payment/checkout/4fe7bb6bedbd94689e89"
}
```

### Réponse d'erreur

```json
{
  "success": 0,
  "errors": [
    "Le champ item_name est obligatoire",
    "Le montant doit être supérieur à 0"
  ]
}
```

## Implémentations par langage

### PHP

```php
<?php
class PayTechPayment {
    private $apiKey;
    private $apiSecret;
    private $baseUrl = 'https://paytech.sn/api';
    
    public function __construct($apiKey, $apiSecret) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }
    
    public function requestPayment($params) {
        $url = $this->baseUrl . '/payment/request-payment';
        
        $headers = [
            'Content-Type: application/json',
            'API_KEY: ' . $this->apiKey,
            'API_SECRET: ' . $this->apiSecret
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            throw new Exception('Erreur cURL: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        $data = json_decode($response, true);
        
        if ($httpCode !== 200) {
            throw new Exception('Erreur API: ' . ($data['message'] ?? 'Erreur inconnue'));
        }
        
        return $data;
    }
}

// Utilisation
try {
    $paytech = new PayTechPayment(
        'votre_api_key',
        'votre_api_secret'
    );
    
    $paymentData = [
        'item_name' => 'Produit Test',
        'item_price' => 5000,
        'currency' => 'XOF',
        'ref_command' => 'CMD_' . uniqid(),
        'command_name' => 'Commande de test',
        'env' => 'test'
    ];
    
    $result = $paytech->requestPayment($paymentData);
    
    if ($result['success']) {
        // Rediriger vers la page de paiement
        header('Location: ' . $result['redirect_url']);
        exit;
    } else {
        echo 'Erreur: ' . implode(', ', $result['errors']);
    }
} catch (Exception $e) {
    echo 'Erreur: ' . $e->getMessage();
}
?>
```

### Node.js

```javascript
const axios = require('axios');

class PayTechPayment {
    constructor(apiKey, apiSecret) {
        this.apiKey = apiKey;
        this.apiSecret = apiSecret;
        this.baseUrl = 'https://paytech.sn/api';
    }
    
    async requestPayment(params) {
        try {
            const response = await axios.post(
                `${this.baseUrl}/payment/request-payment`,
                params,
                {
                    headers: {
                        'Content-Type': 'application/json',
                        'API_KEY': this.apiKey,
                        'API_SECRET': this.apiSecret
                    },
                    timeout: 30000
                }
            );
            
            return response.data;
        } catch (error) {
            if (error.response) {
                throw new Error(`Erreur API: ${error.response.data.message || 'Erreur inconnue'}`);
            } else if (error.request) {
                throw new Error('Pas de réponse du serveur');
            } else {
                throw new Error(`Erreur de requête: ${error.message}`);
            }
        }
    }
}

// Utilisation avec Express.js
const express = require('express');
const app = express();

app.use(express.json());

app.post('/request-payment', async (req, res) => {
    try {
        const paytech = new PayTechPayment(
            process.env.PAYTECH_API_KEY,
            process.env.PAYTECH_API_SECRET
        );
        
        const paymentData = {
            item_name: req.body.item_name,
            item_price: req.body.item_price,
            currency: req.body.currency || 'XOF',
            ref_command: `CMD_${Date.now()}`,
            command_name: req.body.command_name,
            env: process.env.NODE_ENV === 'production' ? 'prod' : 'test'
        };
        
        const result = await paytech.requestPayment(paymentData);
        
        if (result.success) {
            res.json({
                success: true,
                token: result.token,
                redirect_url: result.redirect_url
            });
        } else {
            res.status(400).json({
                success: false,
                errors: result.errors
            });
        }
    } catch (error) {
        console.error('Erreur PayTech:', error);
        res.status(500).json({
            success: false,
            message: 'Erreur interne du serveur'
        });
    }
});
```

### Python

```python
import requests
import json
from typing import Dict, Any

class PayTechPayment:
    def __init__(self, api_key: str, api_secret: str):
        self.api_key = api_key
        self.api_secret = api_secret
        self.base_url = 'https://paytech.sn/api'
    
    def request_payment(self, params: Dict[str, Any]) -> Dict[str, Any]:
        url = f"{self.base_url}/payment/request-payment"
        
        headers = {
            'Content-Type': 'application/json',
            'API_KEY': self.api_key,
            'API_SECRET': self.api_secret
        }
        
        try:
            response = requests.post(
                url,
                json=params,
                headers=headers,
                timeout=30
            )
            
            response.raise_for_status()
            return response.json()
            
        except requests.exceptions.RequestException as e:
            raise Exception(f"Erreur de requête: {str(e)}")
        except json.JSONDecodeError:
            raise Exception("Réponse JSON invalide")

# Utilisation avec Flask
from flask import Flask, request, jsonify
import os

app = Flask(__name__)

@app.route('/request-payment', methods=['POST'])
def request_payment():
    try:
        paytech = PayTechPayment(
            os.getenv('PAYTECH_API_KEY'),
            os.getenv('PAYTECH_API_SECRET')
        )
        
        data = request.get_json()
        
        payment_data = {
            'item_name': data['item_name'],
            'item_price': data['item_price'],
            'currency': data.get('currency', 'XOF'),
            'ref_command': f"CMD_{int(time.time())}",
            'command_name': data['command_name'],
            'env': 'prod' if os.getenv('FLASK_ENV') == 'production' else 'test'
        }
        
        result = paytech.request_payment(payment_data)
        
        if result.get('success'):
            return jsonify({
                'success': True,
                'token': result['token'],
                'redirect_url': result['redirect_url']
            })
        else:
            return jsonify({
                'success': False,
                'errors': result.get('errors', ['Erreur inconnue'])
            }), 400
            
    except Exception as e:
        return jsonify({
            'success': False,
            'message': str(e)
        }), 500
```

## Gestion des erreurs

### Codes d'erreur HTTP

| Code | Description |
|------|-------------|
| 200 | Succès |
| 400 | Paramètres invalides |
| 401 | Authentification échouée |
| 403 | Accès refusé |
| 429 | Trop de requêtes |
| 500 | Erreur serveur |

### Erreurs de validation courantes

**Paramètres manquants**
```json
{
  "success": 0,
  "errors": [
    "Le champ item_name est obligatoire",
    "Le champ item_price est obligatoire"
  ]
}
```

**Montant invalide**
```json
{
  "success": 0,
  "errors": [
    "Le montant doit être supérieur à 0",
    "Le montant ne peut pas dépasser 10000000"
  ]
}
```

**Devise non supportée**
```json
{
  "success": 0,
  "errors": [
    "La devise 'JPY' n'est pas supportée"
  ]
}
```

## Bonnes pratiques

### Génération de références uniques

```php
// PHP - Référence basée sur timestamp et ID utilisateur
$ref_command = 'CMD_' . date('Ymd_His') . '_' . $user_id;

// Ou avec UUID
$ref_command = 'CMD_' . uniqid() . '_' . $user_id;
```

```javascript
// Node.js - Référence avec UUID
const { v4: uuidv4 } = require('uuid');
const ref_command = `CMD_${uuidv4()}`;

// Ou avec timestamp
const ref_command = `CMD_${Date.now()}_${userId}`;
```

### Validation côté client

```javascript
function validatePaymentData(data) {
    const errors = [];
    
    if (!data.item_name || data.item_name.trim().length === 0) {
        errors.push('Le nom du produit est obligatoire');
    }
    
    if (!data.item_price || data.item_price <= 0) {
        errors.push('Le prix doit être supérieur à 0');
    }
    
    if (data.item_price > 10000000) {
        errors.push('Le prix ne peut pas dépasser 10 000 000');
    }
    
    if (!data.command_name || data.command_name.trim().length === 0) {
        errors.push('La description de la commande est obligatoire');
    }
    
    return errors;
}
```

### Gestion des timeouts

```php
// PHP - Configuration timeout cURL
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
```

```javascript
// Node.js - Configuration timeout Axios
const response = await axios.post(url, data, {
    timeout: 30000, // 30 secondes
    headers: headers
});
```

### Logging et monitoring

```php
// PHP - Log des requêtes
error_log("PayTech Request: " . json_encode($paymentData));
error_log("PayTech Response: " . $response);
```

```javascript
// Node.js - Log avec Winston
const winston = require('winston');

const logger = winston.createLogger({
    level: 'info',
    format: winston.format.json(),
    transports: [
        new winston.transports.File({ filename: 'paytech.log' })
    ]
});

logger.info('PayTech Request', { data: paymentData });
logger.info('PayTech Response', { response: result });
```

## Intégration avec le frontend

### Redirection automatique

```javascript
// Redirection côté client après obtention du token
fetch('/api/request-payment', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(paymentData)
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        // Redirection vers PayTech
        window.location.href = data.redirect_url;
    } else {
        console.error('Erreur:', data.errors);
    }
});
```

### Ouverture en popup

```javascript
// Ouverture de la page de paiement en popup
function openPaymentPopup(redirectUrl) {
    const popup = window.open(
        redirectUrl,
        'paytech_payment',
        'width=800,height=600,scrollbars=yes,resizable=yes'
    );
    
    // Surveillance de la fermeture du popup
    const checkClosed = setInterval(() => {
        if (popup.closed) {
            clearInterval(checkClosed);
            // Vérifier le statut du paiement
            checkPaymentStatus();
        }
    }, 1000);
}
```

---

> **Note** : Conservez toujours une trace des références de commande (`ref_command`) dans votre base de données pour pouvoir réconcilier les paiements avec les notifications IPN.


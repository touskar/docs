# Sécurité et signatures HMAC

PayTech utilise des signatures HMAC SHA256 pour garantir l'intégrité et l'authenticité des communications entre votre serveur et les serveurs PayTech. Cette section explique comment implémenter et valider ces signatures.

## Principe des signatures HMAC

### Qu'est-ce que HMAC ?

HMAC (Hash-based Message Authentication Code) est un mécanisme de signature cryptographique qui utilise une fonction de hachage (SHA256 dans le cas de PayTech) combinée avec une clé secrète pour générer une signature unique d'un message.

### Avantages de HMAC SHA256

- **Intégrité** : Détecte toute modification du message
- **Authenticité** : Prouve que le message provient du détenteur de la clé secrète
- **Non-répudiation** : Le signataire ne peut nier avoir envoyé le message
- **Résistance aux attaques** : SHA256 est cryptographiquement sûr

## Génération de signatures HMAC

### Fonction JavaScript

```javascript
function generateHMACSHA256(message, secretKey) {
    try {
        const crypto = require('crypto');
        
        const hmac = crypto.createHmac('sha256', secretKey);
        hmac.update(message);
        return hmac.digest('hex');
    } catch (e) {
        console.log(e);
        return e.message;
    }
}

// Exemple d'utilisation
const message = "item_name=iPhone&item_price=650000&ref_command=CMD_001";
const secretKey = "votre_api_secret";
const signature = generateHMACSHA256(message, secretKey);
console.log('Signature:', signature);
```

### Fonction PHP

```php
<?php
function generateHMACSHA256($message, $secretKey) {
    try {
        return hash_hmac('sha256', $message, $secretKey);
    } catch (Exception $e) {
        error_log("Erreur génération HMAC: " . $e->getMessage());
        return false;
    }
}

// Exemple d'utilisation
$message = "item_name=iPhone&item_price=650000&ref_command=CMD_001";
$secretKey = "votre_api_secret";
$signature = generateHMACSHA256($message, $secretKey);
echo "Signature: " . $signature;
?>
```

### Fonction Python

```python
import hmac
import hashlib

def generate_hmac_sha256(message, secret_key):
    try:
        # Convertir en bytes si nécessaire
        if isinstance(message, str):
            message = message.encode('utf-8')
        if isinstance(secret_key, str):
            secret_key = secret_key.encode('utf-8')
        
        # Générer la signature HMAC
        signature = hmac.new(
            secret_key,
            message,
            hashlib.sha256
        ).hexdigest()
        
        return signature
    except Exception as e:
        print(f"Erreur génération HMAC: {e}")
        return None

# Exemple d'utilisation
message = "item_name=iPhone&item_price=650000&ref_command=CMD_001"
secret_key = "votre_api_secret"
signature = generate_hmac_sha256(message, secret_key)
print(f"Signature: {signature}")
```

### Fonction Java

```java
import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;
import java.security.InvalidKeyException;
import java.security.NoSuchAlgorithmException;

public class PayTechSecurity {
    
    public static String generateHMACSHA256(String message, String secretKey) {
        try {
            Mac mac = Mac.getInstance("HmacSHA256");
            SecretKeySpec secretKeySpec = new SecretKeySpec(
                secretKey.getBytes("UTF-8"), 
                "HmacSHA256"
            );
            mac.init(secretKeySpec);
            
            byte[] hash = mac.doFinal(message.getBytes("UTF-8"));
            
            // Convertir en hexadécimal
            StringBuilder hexString = new StringBuilder();
            for (byte b : hash) {
                String hex = Integer.toHexString(0xff & b);
                if (hex.length() == 1) {
                    hexString.append('0');
                }
                hexString.append(hex);
            }
            
            return hexString.toString();
        } catch (NoSuchAlgorithmException | InvalidKeyException | 
                 java.io.UnsupportedEncodingException e) {
            System.err.println("Erreur génération HMAC: " + e.getMessage());
            return null;
        }
    }
    
    // Exemple d'utilisation
    public static void main(String[] args) {
        String message = "item_name=iPhone&item_price=650000&ref_command=CMD_001";
        String secretKey = "votre_api_secret";
        String signature = generateHMACSHA256(message, secretKey);
        System.out.println("Signature: " + signature);
    }
}
```

## Validation des signatures PayTech

### Validation des notifications IPN

PayTech envoie des hash SHA256 simples (non HMAC) de vos clés API dans les notifications IPN :

```javascript
function validateIPNFromPayTech(data, apiKey, apiSecret) {
    const crypto = require('crypto');
    
    const receivedApiKeyHash = data.api_key_sha256;
    const receivedSecretHash = data.api_secret_sha256;
    
    // PayTech utilise des hash SHA256 simples pour les clés
    const expectedApiKeyHash = crypto.createHash('sha256')
        .update(apiKey)
        .digest('hex');
    const expectedSecretHash = crypto.createHash('sha256')
        .update(apiSecret)
        .digest('hex');
    
    return (receivedApiKeyHash === expectedApiKeyHash && 
            receivedSecretHash === expectedSecretHash);
}

// Exemple d'utilisation
const ipnData = {
    type_event: "sale_complete",
    ref_command: "CMD_001",
    item_price: 650000,
    api_key_sha256: "dacbde6382f4bf6ecf4dcec0624712abec1c02b7e5514dad23fdf1242c70d9b5",
    api_secret_sha256: "91b1ae073d5edd8f3d71ac2fb88c90018c70c9b30993513de15b1757958ab0d3"
};

const isValid = validateIPNFromPayTech(
    ipnData, 
    "votre_api_key", 
    "votre_api_secret"
);

console.log("IPN valide:", isValid);
```

### Validation avancée avec signature de payload

Pour une sécurité renforcée, vous pouvez implémenter une validation HMAC du payload complet :

```javascript
function validateIPNWithHMAC(data, apiSecret) {
    // Extraire la signature reçue
    const receivedSignature = data.signature;
    delete data.signature; // Retirer la signature du payload
    
    // Créer le message à signer (tri des clés pour cohérence)
    const sortedKeys = Object.keys(data).sort();
    const message = sortedKeys
        .map(key => `${key}=${data[key]}`)
        .join('&');
    
    // Générer la signature attendue
    const expectedSignature = generateHMACSHA256(message, apiSecret);
    
    // Comparaison sécurisée
    return timingSafeEqual(receivedSignature, expectedSignature);
}

// Comparaison sécurisée contre les attaques de timing
function timingSafeEqual(a, b) {
    if (a.length !== b.length) {
        return false;
    }
    
    let result = 0;
    for (let i = 0; i < a.length; i++) {
        result |= a.charCodeAt(i) ^ b.charCodeAt(i);
    }
    
    return result === 0;
}
```

## Sécurisation des requêtes sortantes

### Signature des requêtes vers PayTech

```javascript
class PayTechSecureClient {
    constructor(apiKey, apiSecret) {
        this.apiKey = apiKey;
        this.apiSecret = apiSecret;
        this.baseUrl = 'https://paytech.sn/api';
    }
    
    async makeSecureRequest(endpoint, data) {
        // Ajouter timestamp pour éviter les attaques de replay
        data.timestamp = Math.floor(Date.now() / 1000);
        
        // Créer le message à signer
        const message = this.createSignatureMessage(data);
        
        // Générer la signature
        const signature = generateHMACSHA256(message, this.apiSecret);
        
        // Ajouter la signature aux headers
        const headers = {
            'Content-Type': 'application/json',
            'API_KEY': this.apiKey,
            'API_SECRET': this.apiSecret,
            'X-PayTech-Signature': signature,
            'X-PayTech-Timestamp': data.timestamp
        };
        
        try {
            const response = await fetch(`${this.baseUrl}${endpoint}`, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(data)
            });
            
            return await response.json();
        } catch (error) {
            console.error('Erreur requête sécurisée:', error);
            throw error;
        }
    }
    
    createSignatureMessage(data) {
        // Trier les clés pour assurer la cohérence
        const sortedKeys = Object.keys(data).sort();
        return sortedKeys
            .map(key => `${key}=${data[key]}`)
            .join('&');
    }
}

// Utilisation
const client = new PayTechSecureClient('votre_api_key', 'votre_api_secret');

const paymentData = {
    item_name: 'iPhone 13 Pro',
    item_price: 650000,
    currency: 'XOF',
    ref_command: 'CMD_001',
    command_name: 'Achat iPhone'
};

client.makeSecureRequest('/payment/request-payment', paymentData)
    .then(result => console.log('Résultat:', result))
    .catch(error => console.error('Erreur:', error));
```

## Middleware de validation Express.js

```javascript
const express = require('express');
const crypto = require('crypto');

// Middleware de validation des signatures PayTech
function validatePayTechSignature(apiSecret) {
    return (req, res, next) => {
        try {
            const receivedSignature = req.headers['x-paytech-signature'];
            const timestamp = req.headers['x-paytech-timestamp'];
            
            if (!receivedSignature || !timestamp) {
                return res.status(401).json({
                    error: 'Signature ou timestamp manquant'
                });
            }
            
            // Vérifier que le timestamp n'est pas trop ancien (5 minutes max)
            const currentTime = Math.floor(Date.now() / 1000);
            if (currentTime - parseInt(timestamp) > 300) {
                return res.status(401).json({
                    error: 'Timestamp trop ancien'
                });
            }
            
            // Créer le message à valider
            const data = { ...req.body, timestamp: timestamp };
            const message = Object.keys(data)
                .sort()
                .map(key => `${key}=${data[key]}`)
                .join('&');
            
            // Générer la signature attendue
            const expectedSignature = generateHMACSHA256(message, apiSecret);
            
            // Validation sécurisée
            if (!timingSafeEqual(receivedSignature, expectedSignature)) {
                return res.status(401).json({
                    error: 'Signature invalide'
                });
            }
            
            next();
        } catch (error) {
            console.error('Erreur validation signature:', error);
            res.status(500).json({
                error: 'Erreur interne de validation'
            });
        }
    };
}

// Utilisation du middleware
const app = express();
app.use(express.json());

app.post('/webhooks/paytech', 
    validatePayTechSignature(process.env.PAYTECH_API_SECRET),
    (req, res) => {
        // La signature a été validée, traiter la notification
        console.log('Notification PayTech validée:', req.body);
        res.send('IPN OK');
    }
);
```

## Gestion des clés et rotation

### Stockage sécurisé des clés

```javascript
// Utilisation de variables d'environnement
const config = {
    apiKey: process.env.PAYTECH_API_KEY,
    apiSecret: process.env.PAYTECH_API_SECRET,
    // Clé de rotation pour la transition
    apiSecretOld: process.env.PAYTECH_API_SECRET_OLD
};

// Validation avec support de rotation de clés
function validateWithKeyRotation(signature, message, currentSecret, oldSecret) {
    // Essayer avec la clé actuelle
    const currentSignature = generateHMACSHA256(message, currentSecret);
    if (timingSafeEqual(signature, currentSignature)) {
        return { valid: true, keyUsed: 'current' };
    }
    
    // Essayer avec l'ancienne clé si disponible
    if (oldSecret) {
        const oldSignature = generateHMACSHA256(message, oldSecret);
        if (timingSafeEqual(signature, oldSignature)) {
            return { valid: true, keyUsed: 'old' };
        }
    }
    
    return { valid: false, keyUsed: null };
}
```

### Audit et logging des signatures

```javascript
const winston = require('winston');

const logger = winston.createLogger({
    level: 'info',
    format: winston.format.combine(
        winston.format.timestamp(),
        winston.format.json()
    ),
    transports: [
        new winston.transports.File({ filename: 'paytech-security.log' })
    ]
});

function auditSignatureValidation(result, request) {
    logger.info('Signature validation', {
        valid: result.valid,
        keyUsed: result.keyUsed,
        endpoint: request.url,
        ip: request.ip,
        userAgent: request.get('User-Agent'),
        timestamp: new Date().toISOString()
    });
    
    if (!result.valid) {
        logger.warn('Invalid signature attempt', {
            endpoint: request.url,
            ip: request.ip,
            headers: request.headers
        });
    }
}
```

## Tests de sécurité

### Test unitaire des signatures

```javascript
const assert = require('assert');

describe('PayTech HMAC Signatures', () => {
    const testSecret = 'test_secret_key';
    const testMessage = 'item_name=Test&item_price=1000&ref_command=TEST_001';
    
    it('should generate consistent HMAC signatures', () => {
        const signature1 = generateHMACSHA256(testMessage, testSecret);
        const signature2 = generateHMACSHA256(testMessage, testSecret);
        
        assert.strictEqual(signature1, signature2);
        assert.strictEqual(signature1.length, 64); // SHA256 hex = 64 chars
    });
    
    it('should validate correct signatures', () => {
        const signature = generateHMACSHA256(testMessage, testSecret);
        const isValid = timingSafeEqual(
            signature, 
            generateHMACSHA256(testMessage, testSecret)
        );
        
        assert.strictEqual(isValid, true);
    });
    
    it('should reject invalid signatures', () => {
        const validSignature = generateHMACSHA256(testMessage, testSecret);
        const invalidSignature = validSignature.slice(0, -1) + 'x';
        
        const isValid = timingSafeEqual(validSignature, invalidSignature);
        assert.strictEqual(isValid, false);
    });
    
    it('should handle different message formats', () => {
        const messages = [
            'simple=test',
            'item_name=Test Product&item_price=5000',
            'complex={"user":123,"data":"test"}'
        ];
        
        messages.forEach(message => {
            const signature = generateHMACSHA256(message, testSecret);
            assert.strictEqual(typeof signature, 'string');
            assert.strictEqual(signature.length, 64);
        });
    });
});
```

### Test d'intégration avec PayTech

```javascript
async function testPayTechIntegration() {
    const testData = {
        item_name: 'Test Product',
        item_price: 100, // 100 FCFA en mode test
        currency: 'XOF',
        ref_command: `TEST_${Date.now()}`,
        command_name: 'Test de signature',
        env: 'test'
    };
    
    try {
        const client = new PayTechSecureClient(
            process.env.PAYTECH_API_KEY,
            process.env.PAYTECH_API_SECRET
        );
        
        const result = await client.makeSecureRequest(
            '/payment/request-payment', 
            testData
        );
        
        console.log('Test réussi:', result);
        return result.success === 1;
    } catch (error) {
        console.error('Test échoué:', error);
        return false;
    }
}
```

## Bonnes pratiques de sécurité

### Checklist de sécurité

- ✅ **Clés secrètes** : Stockées dans des variables d'environnement
- ✅ **HTTPS obligatoire** : Toutes les communications chiffrées
- ✅ **Validation timestamps** : Protection contre les attaques de replay
- ✅ **Comparaison sécurisée** : Utilisation de `timingSafeEqual`
- ✅ **Logging d'audit** : Traçabilité des validations
- ✅ **Rotation des clés** : Procédure de renouvellement
- ✅ **Tests automatisés** : Validation continue de la sécurité

### Recommandations

1. **Ne jamais exposer les clés secrètes** dans le code source ou les logs
2. **Valider systématiquement** toutes les signatures reçues
3. **Implémenter des timeouts** pour éviter les attaques de déni de service
4. **Monitorer les échecs** de validation pour détecter les tentatives d'attaque
5. **Utiliser des bibliothèques cryptographiques** éprouvées
6. **Tester régulièrement** la sécurité de l'implémentation

---

> **Important** : La sécurité est cruciale dans les systèmes de paiement. Testez toujours vos implémentations de signature en mode test avant de passer en production.


# Intégration Node.js

Ce guide vous explique comment intégrer PayTech dans vos applications Node.js en utilisant les requêtes HTTP.

## Installation des dépendances

```bash
npm install axios crypto
# ou avec yarn
yarn add axios crypto
```

## Configuration de base

```javascript
const axios = require('axios');
const crypto = require('crypto');

// Configuration PayTech
const PAYTECH_CONFIG = {
    API_KEY: process.env.PAYTECH_API_KEY || 'votre_api_key',
    SECRET_KEY: process.env.PAYTECH_SECRET_KEY || 'votre_secret_key',
    ENV: process.env.PAYTECH_ENV || 'test', // 'test' ou 'prod'
    BASE_URL: 'https://paytech.sn/api/payment'
};
```

## Demande de paiement

### Fonction de base

```javascript
async function requestPayment(itemName, itemPrice, refCommand, customField = {}) {
    const url = `${PAYTECH_CONFIG.BASE_URL}/request-payment`;
    
    const payload = {
        item_name: itemName,
        item_price: itemPrice,
        currency: 'xof',
        ref_command: refCommand,
        command_name: itemName,
        env: PAYTECH_CONFIG.ENV,
        success_url: "https://votre-site.com/success",
        cancel_url: "https://votre-site.com/cancel",
        ipn_url: "https://votre-site.com/ipn",
        custom_field: JSON.stringify(customField),
    };
    
    const headers = {
        'API_KEY': PAYTECH_CONFIG.API_KEY,
        'API_SECRET': PAYTECH_CONFIG.SECRET_KEY,
        'Content-Type': 'application/json'
    };
    
    try {
        const response = await axios.post(url, payload, { headers });
        
        if (response.status === 200 || response.status === 201) {
            return {
                success: true,
                data: response.data
            };
        } else {
            return {
                success: false,
                error: `Erreur HTTP ${response.status}`,
                details: response.data
            };
        }
        
    } catch (error) {
        return {
            success: false,
            error: error.message,
            details: error.response?.data
        };
    }
}
```

### Exemple d'utilisation

```javascript
// Créer une demande de paiement
async function createPayment() {
    try {
        const result = await requestPayment(
            "Produit Test",
            1000,
            "CMD_001",
            { user_id: 123, order_type: "online" }
        );
        
        if (result.success) {
            const { token, redirect_url } = result.data;
            
            console.log('Paiement créé avec succès!');
            console.log('Token:', token);
            console.log('URL de redirection:', redirect_url);
            
            return { token, redirect_url };
        } else {
            console.error('Erreur:', result.error);
            throw new Error(result.error);
        }
        
    } catch (error) {
        console.error('Erreur lors de la création du paiement:', error);
        throw error;
    }
}
```

## Validation des notifications IPN

### Fonction de validation

```javascript
function validateIPN(requestData) {
    const receivedApiKeyHash = requestData.api_key_sha256;
    const receivedSecretHash = requestData.api_secret_sha256;
    
    const expectedApiKeyHash = crypto
        .createHash('sha256')
        .update(PAYTECH_CONFIG.API_KEY)
        .digest('hex');
        
    const expectedSecretHash = crypto
        .createHash('sha256')
        .update(PAYTECH_CONFIG.SECRET_KEY)
        .digest('hex');
    
    return (receivedApiKeyHash === expectedApiKeyHash && 
            receivedSecretHash === expectedSecretHash);
}

function processIPN(requestData) {
    if (!validateIPN(requestData)) {
        throw new Error('IPN non valide');
    }
    
    const typeEvent = requestData.type_event;
    
    switch (typeEvent) {
        case 'sale_complete':
            console.log(`Paiement réussi pour la commande ${requestData.ref_command}`);
            // Mettre à jour votre base de données
            break;
            
        case 'sale_cancel':
            console.log('Paiement annulé');
            break;
            
        default:
            console.log(`Événement non géré: ${typeEvent}`);
    }
    
    return { status: 'processed' };
}
```

## Intégration Express.js

```javascript
const express = require('express');
const bodyParser = require('body-parser');

const app = express();

// Middleware pour parser les données POST
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Route pour créer un paiement
app.post('/payment/create', async (req, res) => {
    try {
        const { item_name, item_price, ref_command, custom_field } = req.body;
        
        const result = await requestPayment(
            item_name,
            item_price,
            ref_command,
            custom_field || {}
        );
        
        res.json(result);
        
    } catch (error) {
        res.status(400).json({
            success: false,
            error: error.message
        });
    }
});

// Route pour traiter les notifications IPN
app.post('/payment/ipn', (req, res) => {
    try {
        // PayTech envoie les données en POST form-encoded
        const requestData = req.body;
        
        const result = processIPN(requestData);
        
        res.status(200).send('OK');
        
    } catch (error) {
        console.error('Erreur IPN:', error);
        res.status(400).send('ERROR');
    }
});

// Route de succès
app.get('/payment/success', (req, res) => {
    const { token, ref_command } = req.query;
    
    res.send(`
        <h1>Paiement réussi!</h1>
        <p>Token: ${token}</p>
        <p>Référence: ${ref_command}</p>
    `);
});

// Route d'annulation
app.get('/payment/cancel', (req, res) => {
    const { token, ref_command } = req.query;
    
    res.send(`
        <h1>Paiement annulé</h1>
        <p>Token: ${token}</p>
        <p>Référence: ${ref_command}</p>
    `);
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Serveur démarré sur le port ${PORT}`);
});
```

## Intégration avec base de données

### Avec MongoDB (Mongoose)

```javascript
const mongoose = require('mongoose');

// Schéma pour les transactions
const transactionSchema = new mongoose.Schema({
    ref_command: { type: String, required: true, unique: true },
    token: { type: String, required: true },
    amount: { type: Number, required: true },
    status: { 
        type: String, 
        enum: ['pending', 'completed', 'cancelled', 'failed'],
        default: 'pending'
    },
    custom_field: { type: Object },
    created_at: { type: Date, default: Date.now },
    updated_at: { type: Date, default: Date.now }
});

const Transaction = mongoose.model('Transaction', transactionSchema);

// Fonction pour sauvegarder une transaction
async function saveTransaction(paymentData, customField = {}) {
    try {
        const transaction = new Transaction({
            ref_command: paymentData.ref_command,
            token: paymentData.token,
            amount: paymentData.item_price,
            custom_field: customField
        });
        
        await transaction.save();
        console.log('Transaction sauvegardée:', transaction._id);
        
        return transaction;
        
    } catch (error) {
        console.error('Erreur lors de la sauvegarde:', error);
        throw error;
    }
}

// Fonction pour mettre à jour le statut
async function updateTransactionStatus(refCommand, status) {
    try {
        const transaction = await Transaction.findOneAndUpdate(
            { ref_command: refCommand },
            { 
                status: status,
                updated_at: new Date()
            },
            { new: true }
        );
        
        if (!transaction) {
            throw new Error(`Transaction non trouvée: ${refCommand}`);
        }
        
        console.log('Statut mis à jour:', transaction);
        return transaction;
        
    } catch (error) {
        console.error('Erreur lors de la mise à jour:', error);
        throw error;
    }
}
```

## Gestion des erreurs avancée

```javascript
class PayTechError extends Error {
    constructor(message, code, details) {
        super(message);
        this.name = 'PayTechError';
        this.code = code;
        this.details = details;
    }
}

function handlePayTechErrors(error) {
    const errorCodes = {
        '01': 'Paramètres manquants',
        '02': 'Clés API invalides',
        '03': 'Montant invalide',
        '04': 'Devise non supportée',
        '05': 'Erreur de communication'
    };
    
    if (error.response && error.response.data) {
        const { error_code, message } = error.response.data;
        const errorMessage = errorCodes[error_code] || message || 'Erreur inconnue';
        
        throw new PayTechError(errorMessage, error_code, error.response.data);
    }
    
    throw new PayTechError(error.message, 'UNKNOWN', error);
}

// Utilisation avec gestion d'erreurs
async function createPaymentWithErrorHandling(itemName, itemPrice, refCommand) {
    try {
        const result = await requestPayment(itemName, itemPrice, refCommand);
        
        if (!result.success) {
            throw new Error(result.error);
        }
        
        return result.data;
        
    } catch (error) {
        handlePayTechErrors(error);
    }
}
```

## Tests unitaires

```javascript
const { expect } = require('chai');
const sinon = require('sinon');
const axios = require('axios');

describe('PayTech Integration', () => {
    let axiosStub;
    
    beforeEach(() => {
        axiosStub = sinon.stub(axios, 'post');
    });
    
    afterEach(() => {
        axiosStub.restore();
    });
    
    it('should create payment successfully', async () => {
        // Mock de la réponse PayTech
        const mockResponse = {
            status: 201,
            data: {
                token: 'test_token',
                redirect_url: 'https://paytech.sn/payment/test_token'
            }
        };
        
        axiosStub.resolves(mockResponse);
        
        const result = await requestPayment('Test Product', 1000, 'TEST_001');
        
        expect(result.success).to.be.true;
        expect(result.data).to.have.property('token');
        expect(result.data).to.have.property('redirect_url');
    });
    
    it('should validate IPN correctly', () => {
        const validIPNData = {
            api_key_sha256: crypto
                .createHash('sha256')
                .update(PAYTECH_CONFIG.API_KEY)
                .digest('hex'),
            api_secret_sha256: crypto
                .createHash('sha256')
                .update(PAYTECH_CONFIG.SECRET_KEY)
                .digest('hex')
        };
        
        expect(validateIPN(validIPNData)).to.be.true;
    });
    
    it('should reject invalid IPN', () => {
        const invalidIPNData = {
            api_key_sha256: 'invalid_hash',
            api_secret_sha256: 'invalid_hash'
        };
        
        expect(validateIPN(invalidIPNData)).to.be.false;
    });
});
```

## Configuration pour la production

```javascript
// config/production.js
module.exports = {
    paytech: {
        API_KEY: process.env.PAYTECH_API_KEY,
        SECRET_KEY: process.env.PAYTECH_SECRET_KEY,
        ENV: 'prod',
        BASE_URL: 'https://paytech.sn/api/payment',
        SUCCESS_URL: 'https://votre-site.com/payment/success',
        CANCEL_URL: 'https://votre-site.com/payment/cancel',
        IPN_URL: 'https://votre-site.com/payment/ipn'
    },
    security: {
        enableIPNValidation: true,
        logTransactions: true,
        enableRateLimit: true
    }
};

// Middleware de sécurité
const rateLimit = require('express-rate-limit');

const paymentLimiter = rateLimit({
    windowMs: 15 * 60 * 1000, // 15 minutes
    max: 10, // limite de 10 requêtes par IP
    message: 'Trop de tentatives de paiement, réessayez plus tard.'
});

app.use('/payment/create', paymentLimiter);
```

## Ressources supplémentaires

- [Documentation API PayTech](/)
- [Exemples de code sur GitHub](https://github.com/paytech-sn)
- [Support technique](mailto:support@paytech.sn)
- [NPM Package PayTech](https://www.npmjs.com/search?q=paytech)


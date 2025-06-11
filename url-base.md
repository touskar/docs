# URL de base PayTech

Cette page détaille les URLs de base utilisées par l'API PayTech selon l'environnement.

## 🌐 Environnements disponibles

PayTech propose deux environnements pour vos intégrations :

### Environnement de test
- **URL de base** : `https://paytech.sn/api`
- **Usage** : Développement et tests
- **Données** : Transactions fictives
- **Cartes de test** : Disponibles

### Environnement de production
- **URL de base** : `https://paytech.sn/api`
- **Usage** : Transactions réelles
- **Données** : Transactions réelles
- **Validation** : Compte vérifié requis

## 🔗 Endpoints principaux

### Paiements

#### Demande de paiement
```
POST https://paytech.sn/api/payment/request-payment
```

#### Vérification de statut
```
GET https://paytech.sn/api/payment/check/{token}
```

### Transferts

#### Demande de transfert
```
POST https://paytech.sn/api/transfer/request-transfer
```

#### Statut de transfert
```
GET https://paytech.sn/api/transfer/status/{token}
```

### Utilitaires

#### Validation de compte
```
POST https://paytech.sn/api/account/validate
```

#### Liste des services
```
GET https://paytech.sn/api/services/list
```

## 📱 URLs mobiles spéciales

Pour les intégrations mobiles (Flutter, React Native), utilisez ces URLs spéciales :

### URLs de retour mobile
```javascript
const MOBILE_SUCCESS_URL = "https://paytech.sn/mobile/success";
const MOBILE_CANCEL_URL = "https://paytech.sn/mobile/cancel";
```

Ces URLs permettent aux SDK mobiles de détecter automatiquement les retours de paiement.

## 🔧 Configuration par environnement

### Test (Sandbox)

```javascript
const PAYTECH_CONFIG = {
  baseUrl: 'https://paytech.sn/api',
  env: 'test',
  apiKey: 'votre_api_key_test',
  secretKey: 'votre_secret_key_test'
};
```

### Production

```javascript
const PAYTECH_CONFIG = {
  baseUrl: 'https://paytech.sn/api',
  env: 'prod',
  apiKey: 'votre_api_key_prod',
  secretKey: 'votre_secret_key_prod'
};
```

## 🛡️ Sécurité des URLs

### HTTPS obligatoire
- Toutes les communications doivent utiliser HTTPS
- Les URLs HTTP sont automatiquement redirigées vers HTTPS
- Certificats SSL/TLS valides requis pour les webhooks

### Validation des domaines
- Les URLs de retour doivent être sur des domaines vérifiés
- Les URLs IPN doivent être accessibles publiquement
- Pas de localhost en production

## 📋 Exemples d'intégration

### PHP
```php
<?php
define('PAYTECH_BASE_URL', 'https://paytech.sn/api');
define('PAYTECH_ENV', 'test'); // ou 'prod'

$url = PAYTECH_BASE_URL . '/payment/request-payment';
```

### JavaScript
```javascript
const PAYTECH_BASE_URL = 'https://paytech.sn/api';
const PAYTECH_ENV = 'test'; // ou 'prod'

const url = `${PAYTECH_BASE_URL}/payment/request-payment`;
```

### Python
```python
PAYTECH_BASE_URL = 'https://paytech.sn/api'
PAYTECH_ENV = 'test'  # ou 'prod'

url = f'{PAYTECH_BASE_URL}/payment/request-payment'
```

### Java
```java
public static final String PAYTECH_BASE_URL = "https://paytech.sn/api";
public static final String PAYTECH_ENV = "test"; // ou "prod"

String url = PAYTECH_BASE_URL + "/payment/request-payment";
```

## 🔄 Gestion des erreurs réseau

### Timeouts recommandés
- **Connexion** : 10 secondes
- **Lecture** : 30 secondes
- **Total** : 45 secondes

### Retry logic
```javascript
const MAX_RETRIES = 3;
const RETRY_DELAY = 1000; // 1 seconde

async function callPayTechAPI(url, data, retries = 0) {
  try {
    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'API_KEY': 'votre_api_key',
        'API_SECRET': 'votre_secret_key'
      },
      body: JSON.stringify(data),
      timeout: 30000
    });
    
    return await response.json();
  } catch (error) {
    if (retries < MAX_RETRIES) {
      await new Promise(resolve => setTimeout(resolve, RETRY_DELAY));
      return callPayTechAPI(url, data, retries + 1);
    }
    throw error;
  }
}
```

## 🌍 Disponibilité géographique

### Régions supportées
- **Sénégal** : Service complet
- **Mali** : Orange Money uniquement
- **Burkina Faso** : Orange Money uniquement
- **Côte d'Ivoire** : Orange Money uniquement

### Latence réseau
- **Sénégal** : < 50ms
- **Afrique de l'Ouest** : < 200ms
- **Europe** : < 300ms
- **Amérique du Nord** : < 400ms

## 📊 Monitoring et statut

### Page de statut
- **URL** : [https://status.paytech.sn](https://status.paytech.sn)
- **Monitoring** : Temps de réponse en temps réel
- **Incidents** : Notifications automatiques

### Health check
```
GET https://paytech.sn/api/health
```

Réponse :
```json
{
  "status": "ok",
  "timestamp": "2024-01-15T10:30:00Z",
  "version": "2.1.0"
}
```

## 🔗 URLs utiles

### Dashboard
- **Test** : [https://test.paytech.sn/dashboard](https://test.paytech.sn/dashboard)
- **Production** : [https://paytech.sn/dashboard](https://paytech.sn/dashboard)

### Documentation
- **API Reference** : [https://docs.paytech.sn](https://docs.paytech.sn)
- **Guides** : [https://docs.paytech.sn/#/introduction](https://docs.paytech.sn/#/introduction)

### Support
- **Centre d'aide** : [https://help.paytech.sn](https://help.paytech.sn)
- **Contact** : [https://paytech.sn/contact](https://paytech.sn/contact)

## ⚠️ Notes importantes

1. **Pas de trailing slash** : N'ajoutez pas de `/` à la fin des URLs de base
2. **Case sensitive** : Les endpoints sont sensibles à la casse
3. **Encoding** : Utilisez UTF-8 pour tous les paramètres
4. **Rate limiting** : Maximum 100 requêtes par minute par clé API


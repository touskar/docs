# Clés API PayTech

Les clés API sont essentielles pour authentifier vos requêtes auprès des serveurs PayTech. Cette section explique comment obtenir, configurer et utiliser vos clés API de manière sécurisée.

## Qu'est-ce qu'une clé API ?

Une clé d'interface de programmation d'applications (clé API) est un code unique utilisé par les programmes informatiques pour s'authentifier auprès de l'API PayTech. Chaque compte marchand dispose de deux clés :

- **API_KEY** : Clé publique d'identification
- **API_SECRET** : Clé secrète pour la signature des requêtes

## Obtention des clés API

### Étape 1 : Accès au dashboard

1. Connectez-vous à votre espace client PayTech : [paytech.sn/app](https://paytech.sn/app)
2. Utilisez vos identifiants de connexion (email et mot de passe)
3. Accédez au tableau de bord principal

### Étape 2 : Navigation vers les paramètres API

1. Dans le menu principal, cliquez sur **"Paramètres"**
2. Sélectionnez **"API"** dans le sous-menu
3. Vous accédez à la page de gestion des clés API

### Étape 3 : Récupération des clés

Sur la page API, vous trouverez :
- **API Key** : Votre clé publique d'identification
- **API Secret** : Votre clé secrète (masquée par défaut)
- **Bouton "Régénérer"** : Pour créer de nouvelles clés si nécessaire

> ⚠️ **Important** : Notez vos clés dans un endroit sécurisé. La clé secrète ne sera affichée qu'une seule fois pour des raisons de sécurité.

## Configuration des clés

### Variables d'environnement (Recommandé)

La méthode la plus sécurisée consiste à stocker vos clés dans des variables d'environnement :

```bash
# Fichier .env
PAYTECH_API_KEY=1afac858d4fa5ec74e3e3734c3829793eb6bd5f4602c84ac4a5069369812915e
PAYTECH_API_SECRET=96bc36c11560f2151c4b43eee310cefabc2e9e9000f7e315c3ca3d279e3f98ac
PAYTECH_ENV=test  # ou 'prod' pour la production
```

### Configuration PHP

```php
<?php
// Configuration PayTech
define('PAYTECH_API_KEY', getenv('PAYTECH_API_KEY'));
define('PAYTECH_API_SECRET', getenv('PAYTECH_API_SECRET'));
define('PAYTECH_ENV', getenv('PAYTECH_ENV') ?: 'test');

// URLs de base
define('PAYTECH_API_URL', 'https://paytech.sn/api');
?>
```

### Configuration Node.js

```javascript
// config/paytech.js
module.exports = {
  apiKey: process.env.PAYTECH_API_KEY,
  apiSecret: process.env.PAYTECH_API_SECRET,
  environment: process.env.PAYTECH_ENV || 'test',
  baseUrl: 'https://paytech.sn/api'
};
```

### Configuration Python

```python
# config.py
import os

PAYTECH_CONFIG = {
    'api_key': os.getenv('PAYTECH_API_KEY'),
    'api_secret': os.getenv('PAYTECH_API_SECRET'),
    'environment': os.getenv('PAYTECH_ENV', 'test'),
    'base_url': 'https://paytech.sn/api'
}
```

## Utilisation des clés dans les requêtes

### Headers HTTP requis

Toutes les requêtes vers l'API PayTech doivent inclure les headers d'authentification :

```http
POST /api/payment/request-payment HTTP/1.1
Host: paytech.sn
Content-Type: application/json
API_KEY: 1afac858d4fa5ec74e3e3734c3829793eb6bd5f4602c84ac4a5069369812915e
API_SECRET: 96bc36c11560f2151c4b43eee310cefabc2e9e9000f7e315c3ca3d279e3f98ac
```

### Exemple avec cURL

```bash
curl -X POST "https://paytech.sn/api/payment/request-payment" \
  -H "Content-Type: application/json" \
  -H "API_KEY: 1afac858d4fa5ec74e3e3734c3829793eb6bd5f4602c84ac4a5069369812915e" \
  -H "API_SECRET: 96bc36c11560f2151c4b43eee310cefabc2e9e9000f7e315c3ca3d279e3f98ac" \
  -d '{
    "item_name": "Produit Test",
    "item_price": 1000,
    "currency": "XOF",
    "ref_command": "CMD_001",
    "command_name": "Commande de test"
  }'
```

### Exemple PHP avec cURL

```php
<?php
function makePayTechRequest($endpoint, $data) {
    $url = PAYTECH_API_URL . $endpoint;
    
    $headers = [
        'Content-Type: application/json',
        'API_KEY: ' . PAYTECH_API_KEY,
        'API_SECRET: ' . PAYTECH_API_SECRET
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'status_code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}
?>
```

## Validation des notifications IPN

### Principe de validation

Les notifications IPN incluent des versions hachées de vos clés pour validation :

```json
{
  "api_key_sha256": "dacbde6382f4bf6ecf4dcec0624712abec1c02b7e5514dad23fdf1242c70d9b5",
  "api_secret_sha256": "91b1ae073d5edd8f3d71ac2fb88c90018c70c9b30993513de15b1757958ab0d3"
}
```

### Validation PHP

```php
<?php
function validateIPNFromPayTech($requestData) {
    $receivedApiKeyHash = $requestData['api_key_sha256'];
    $receivedSecretHash = $requestData['api_secret_sha256'];
    
    $expectedApiKeyHash = hash('sha256', PAYTECH_API_KEY);
    $expectedSecretHash = hash('sha256', PAYTECH_API_SECRET);
    
    return ($receivedApiKeyHash === $expectedApiKeyHash && 
            $receivedSecretHash === $expectedSecretHash);
}

// Utilisation
if (validateIPNFromPayTech($_POST)) {
    // La notification provient bien de PayTech
    // Traiter la notification...
} else {
    // Notification non authentifiée
    http_response_code(403);
    exit('Unauthorized');
}
?>
```

### Validation Node.js

```javascript
const crypto = require('crypto');

function validateIPNFromPayTech(requestData) {
    const receivedApiKeyHash = requestData.api_key_sha256;
    const receivedSecretHash = requestData.api_secret_sha256;
    
    const expectedApiKeyHash = crypto.createHash('sha256')
        .update(process.env.PAYTECH_API_KEY)
        .digest('hex');
    const expectedSecretHash = crypto.createHash('sha256')
        .update(process.env.PAYTECH_API_SECRET)
        .digest('hex');
    
    return (receivedApiKeyHash === expectedApiKeyHash && 
            receivedSecretHash === expectedSecretHash);
}
```

## Sécurité des clés API

### Bonnes pratiques

**Stockage sécurisé**
- Ne jamais inclure les clés directement dans le code source
- Utiliser des variables d'environnement ou des fichiers de configuration sécurisés
- Éviter de commiter les clés dans les systèmes de contrôle de version

**Accès restreint**
- Limiter l'accès aux clés aux seules personnes autorisées
- Utiliser des rôles et permissions appropriés
- Auditer régulièrement l'accès aux clés

**Rotation des clés**
- Régénérer les clés périodiquement
- Changer immédiatement les clés en cas de compromission suspectée
- Maintenir une procédure de rotation documentée

### Régénération des clés

Si vous suspectez une compromission de vos clés :

1. Connectez-vous à votre dashboard PayTech
2. Allez dans **Paramètres > API**
3. Cliquez sur **"Régénérer les clés"**
4. Mettez à jour votre configuration avec les nouvelles clés
5. Testez votre intégration avec les nouvelles clés

> ⚠️ **Attention** : La régénération des clés rend les anciennes clés immédiatement inutilisables. Assurez-vous de mettre à jour tous vos systèmes.

## Environnements de test et production

### Clés de test

Les mêmes clés API sont utilisées pour les environnements de test et de production. La différence se fait via le paramètre `env` dans vos requêtes :

```json
{
  "env": "test",  // Pour les tests
  "item_name": "Produit Test",
  "item_price": 1000
}
```

### Passage en production

Pour passer en production :

1. Changez le paramètre `env` de `"test"` à `"prod"`
2. Vérifiez que votre compte est validé pour la production
3. Testez avec de petits montants initialement
4. Surveillez les logs et notifications IPN

## Dépannage

### Erreurs d'authentification courantes

**401 Unauthorized**
- Vérifiez que vos clés API sont correctes
- Assurez-vous que les headers sont bien envoyés
- Vérifiez l'orthographe des noms de headers

**403 Forbidden**
- Votre compte peut ne pas être activé
- Les clés peuvent avoir été régénérées
- Contactez le support si le problème persiste

### Validation des clés

Pour tester vos clés, utilisez l'endpoint de test :

```bash
curl -X GET "https://paytech.sn/api/test" \
  -H "API_KEY: votre_api_key" \
  -H "API_SECRET: votre_api_secret"
```

Une réponse `200 OK` confirme que vos clés sont valides.

---

> **Support** : En cas de problème avec vos clés API, contactez le support PayTech à support@paytech.sn ou +221 77 245 71 99.


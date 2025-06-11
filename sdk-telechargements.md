# SDK et Téléchargements PayTech

Cette page regroupe tous les SDK officiels, bibliothèques et outils de développement PayTech pour faciliter l'intégration sur différentes plateformes.

## 📦 Dépôts officiels

Tous les fichiers sont disponibles et maintenus par l'équipe PayTech. Ils sont régulièrement mis à jour pour assurer la compatibilité avec les dernières versions des plateformes.

### 🛒 E-commerce et CMS

#### WordPress - WooCommerce
- **Version** : PayTech 6.0.3
- **Dernière mise à jour** : 07 Avril 2025 12H02
- **Support** : Mise à jour WooCommerce
- **Téléchargement** : [paytech_woocommerce.zip](https://doc.paytech.sn/downloads/sdk/woocomerce/paytech_woocommerce.zip?v=6.0.3)

#### Drupal Commerce
- **Version** : 5.0.0
- **Téléchargement** : [commerce_paytech.zip](https://doc.paytech.sn/downloads/sdk/drupal/commerce_paytech.zip?v=5.0.0)

#### PrestaShop
- **Version** : 1.0
- **Téléchargement** : [paytech.zip](https://doc.paytech.sn/downloads/sdk/prestashop/paytech.zip?v=1)

### 💻 SDK Serveur

#### PHP
- **Description** : SDK PHP complet pour intégration serveur
- **Téléchargement** : [paytech_php.zip](https://doc.paytech.sn/downloads/sdk/paytech_php.zip)
- **Utilisation** : Intégration côté serveur, gestion des paiements et IPN

### 📱 SDK Mobile

#### Android
- **Description** : Library & Projet test Android
- **Téléchargement** : [paytech_android.zip](https://doc.paytech.sn/downloads/sdk/paytech_android.zip)
- **Contenu** : 
  - Bibliothèque PayTech Android (.aar)
  - Projet d'exemple complet
  - Documentation d'intégration

#### iOS
- **Description** : Library iOS via CocoaPods
- **Installation** : [CocoaPods - Paytech](https://cocoapods.org/pods/Paytech)
- **Commande** : `pod 'Paytech'`

### 🧪 Outils de test

#### Boutique de démonstration
- **Description** : Application de test complète
- **Téléchargement** : [shop_test.zip](https://doc.paytech.sn/downloads/sdk/shop_test.zip)
- **Utilisation** : Test des intégrations, exemples d'implémentation

## 📋 Guide d'installation

### WordPress/WooCommerce

1. **Télécharger** le plugin PayTech WooCommerce
2. **Installer** via l'interface d'administration WordPress
3. **Activer** le plugin dans la liste des extensions
4. **Configurer** vos clés API dans WooCommerce > Paramètres > Paiements

### Drupal Commerce

1. **Télécharger** le module PayTech Commerce
2. **Extraire** dans le dossier modules de Drupal
3. **Activer** le module via l'interface d'administration
4. **Configurer** les paramètres de paiement

### PrestaShop

1. **Télécharger** le module PayTech PrestaShop
2. **Installer** via le gestionnaire de modules
3. **Configurer** les paramètres PayTech
4. **Tester** en mode sandbox

### PHP SDK

```php
<?php
// Inclusion du SDK PayTech
require_once 'paytech_php/PayTech.php';

// Configuration
$paytech = new PayTech([
    'api_key' => 'votre_api_key',
    'api_secret' => 'votre_api_secret',
    'env' => 'test' // ou 'prod'
]);

// Demande de paiement
$response = $paytech->requestPayment([
    'item_name' => 'Produit test',
    'item_price' => 1000,
    'currency' => 'XOF',
    'ref_command' => 'CMD_' . time()
]);
?>
```

### Android SDK

```java
// Ajout de la dépendance dans build.gradle
implementation files('libs/paytech-sdk.aar')

// Utilisation dans votre Activity
PayTech paytech = new PayTech(this);
paytech.setApiKey("votre_api_key");
paytech.setApiSecret("votre_api_secret");
paytech.setEnvironment(PayTech.Environment.TEST);

// Lancement du paiement
PaymentRequest request = new PaymentRequest()
    .setItemName("Produit test")
    .setItemPrice(1000)
    .setCurrency("XOF")
    .setRefCommand("CMD_" + System.currentTimeMillis());

paytech.startPayment(request);
```

### iOS SDK

```swift
// Installation via CocoaPods
// Podfile
pod 'Paytech'

// Utilisation dans votre projet
import Paytech

let paytech = PayTech()
paytech.apiKey = "votre_api_key"
paytech.apiSecret = "votre_api_secret"
paytech.environment = .test

// Configuration du paiement
let request = PaymentRequest()
request.itemName = "Produit test"
request.itemPrice = 1000
request.currency = "XOF"
request.refCommand = "CMD_\(Date().timeIntervalSince1970)"

paytech.startPayment(request: request)
```

## 🔧 Configuration requise

### Serveur
- **PHP** : Version 7.4 ou supérieure
- **HTTPS** : Obligatoire pour la production
- **Extensions** : cURL, JSON, OpenSSL

### WordPress/WooCommerce
- **WordPress** : Version 5.0 ou supérieure
- **WooCommerce** : Version 4.0 ou supérieure
- **PHP** : Version 7.4 ou supérieure

### Android
- **API Level** : 21 (Android 5.0) ou supérieur
- **Permissions** : INTERNET, ACCESS_NETWORK_STATE

### iOS
- **iOS** : Version 11.0 ou supérieure
- **Xcode** : Version 12.0 ou supérieure

## 📚 Documentation complémentaire

### Guides d'intégration
- [Guide PHP détaillé](serveur-php.md)
- [Guide Android complet](mobile-android.md)
- [Guide iOS détaillé](mobile-ios.md)
- [Configuration WooCommerce](cms-wordpress.md)

### Ressources de développement
- [API Reference](demande-paiement.md)
- [Gestion des erreurs](codes-erreur.md)
- [Notifications IPN](ipn-fonctionnement.md)
- [Tests et débogage](tests-debug.md)

## 🆘 Support technique

### Problèmes d'installation
- Vérifiez les prérequis système
- Consultez les logs d'erreur
- Testez en mode sandbox

### Contact support
- **Email** : [support@paytech.sn](mailto:support@paytech.sn)
- **Téléphone** : +221 77 245 71 99
- **Documentation** : [doc.paytech.sn](https://doc.paytech.sn)

---

> 💡 **Conseil** : Commencez toujours par tester avec la boutique de démonstration avant d'intégrer dans votre environnement de production. Cela vous permettra de comprendre le flux de paiement et de valider votre configuration.


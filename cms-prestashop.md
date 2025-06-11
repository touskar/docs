# PrestaShop

Guide d'intégration PayTech pour PrestaShop.

## 📦 Installation

### Téléchargement

**PayTech PrestaShop v1.0**  
[Télécharger paytech.zip]({{DOWNLOAD_URL}}/prestashop/paytech.zip?v=1)

### Méthode 1 : Via l'admin PrestaShop

1. **Téléchargez** le module depuis le lien ci-dessus
2. **Connectez-vous** à votre back-office PrestaShop
3. **Allez** dans Modules > Module Manager
4. **Cliquez** sur "Télécharger un module"
5. **Sélectionnez** le fichier `paytech.zip`
6. **Cliquez** sur "Télécharger ce module"
7. **Installez** et **configurez** le module

### Méthode 2 : Via FTP

1. **Téléchargez** et **décompressez** le module
2. **Uploadez** le dossier `paytech` dans `/modules/`
3. **Allez** dans Modules > Module Manager
4. **Recherchez** "PayTech" et cliquez sur "Installer"

## ⚙️ Configuration

> ✅ **Important** : Le module PayTech PrestaShop gère **automatiquement** les notifications IPN. Vous n'avez pas besoin de configurer manuellement les webhooks ou les endpoints IPN. Il suffit d'installer le module et de renseigner vos clés API.

### 1. Accès aux paramètres

1. **Allez** dans Modules > Module Manager
2. **Recherchez** "PayTech" dans la liste des modules installés
3. **Cliquez** sur "Configurer"

### 2. Configuration de base

#### Paramètres généraux
- **Activer PayTech** : Oui
- **Titre** : "Paiement mobile et carte bancaire"
- **Description** : "Payez avec Orange Money, Tigo Cash, Wave ou carte bancaire"

#### Clés API
- **API Key** : Votre clé publique PayTech
- **API Secret** : Votre clé secrète PayTech
- **Environnement** : Test ou Production

#### Configuration automatique
Le module configure automatiquement :
- **URL de succès** : `https://votre-boutique.com/module/paytech/validation`
- **URL d'annulation** : `https://votre-boutique.com/order`
- **URL IPN** : `https://votre-boutique.com/module/paytech/ipn`

## 🎨 Personnalisation

### Apparence du module

Le module s'intègre automatiquement avec le thème de votre boutique PrestaShop.

#### Personnaliser les couleurs
```css
/* Dans votre thème CSS */
.paytech-payment-option {
    background: #135571;
    color: white;
    border-radius: 5px;
    padding: 15px;
}

.paytech-payment-option:hover {
    background: #2c5aa0;
}

.paytech-logo {
    max-height: 40px;
    width: auto;
}
```

### Logo personnalisé

1. **Remplacez** le fichier `modules/paytech/views/img/paytech-logo.png`
2. **Respectez** les dimensions recommandées : 200x60px
3. **Videz** le cache PrestaShop

## 🔧 Fonctionnalités avancées

### Méthodes de paiement

Le module supporte automatiquement :
- **Orange Money** (Sénégal, Mali, Burkina Faso, Côte d'Ivoire)
- **Tigo Cash** (Sénégal)
- **Wave** (Sénégal, Côte d'Ivoire)
- **Cartes bancaires** (Visa, Mastercard)

### Devises supportées
- **XOF** (Franc CFA) - par défaut
- **EUR** (Euro)
- **USD** (Dollar US)
- **CAD** (Dollar Canadien)
- **GBP** (Livre Sterling)
- **MAD** (Dirham Marocain)

### Gestion des commandes

#### États de commande automatiques
- **En attente de paiement** : Commande créée
- **Paiement accepté** : Paiement confirmé via IPN
- **Erreur de paiement** : Paiement échoué
- **Annulé** : Paiement annulé par le client

#### Emails automatiques
Le module envoie automatiquement :
- Email de confirmation de commande
- Email de confirmation de paiement
- Email d'échec de paiement (optionnel)

## 📱 Support mobile

### Détection automatique
Le module détecte automatiquement les appareils mobiles et optimise l'expérience :
- Interface adaptée aux écrans tactiles
- Redirection optimisée pour les applications mobiles
- Support des deep links pour les apps natives

### Configuration mobile
```php
// Le module gère automatiquement les URLs mobiles
// Aucune configuration supplémentaire requise
```

## 🔔 Notifications IPN

### Configuration automatique
Le module configure automatiquement l'endpoint IPN :
- **URL** : `https://votre-boutique.com/module/paytech/ipn`
- **Méthode** : POST
- **Format** : form-data
- **Sécurité** : Validation automatique des signatures

### Traitement automatique
Le module traite automatiquement :
- Validation des signatures HMAC
- Mise à jour des statuts de commande
- Envoi des emails de confirmation
- Gestion des stocks
- Historique des transactions

## 🛡️ Sécurité

### Validation automatique
Le module valide automatiquement :
- Signatures HMAC des notifications IPN
- Montants des transactions
- États des commandes
- Origine des requêtes

### Logs de sécurité
```php
// Activer les logs détaillés (en mode debug)
// Dans config/defines.inc.php
define('_PS_MODE_DEV_', true);

// Les logs PayTech sont dans /var/logs/
```

## 🧪 Mode test

### Activation du mode test
1. **Configuration PayTech** > Environnement > "Test"
2. **Utilisez** vos clés API de test
3. **Testez** avec les données de test

### Données de test

#### Cartes de test
```
Visa Success: 4111111111111111
Visa Decline: 4000000000000002
Mastercard Success: 5555555555554444
Expiry: 12/25
CVV: 123
```

#### Numéros mobiles de test
```
Orange Money: +221701234567
Tigo Cash: +221761234567
Wave: +221781234567
```

## 📊 Rapports et analytics

### Dashboard PrestaShop
Le module ajoute des statistiques PayTech :
- Transactions par méthode de paiement
- Revenus par période
- Taux de conversion
- Transactions échouées

### Exports
Le module permet d'exporter :
- Liste des transactions PayTech
- Rapports de réconciliation
- Données pour la comptabilité

## 🔧 Dépannage

### Problèmes courants

#### 1. Module non visible au checkout
- Vérifiez que le module est activé
- Vérifiez les restrictions de pays/devises
- Videz le cache PrestaShop

#### 2. IPN non reçus
- Vérifiez que l'URL de la boutique est accessible
- Désactivez le mode maintenance temporairement
- Vérifiez les logs dans `/var/logs/`

#### 3. Erreurs de paiement
```php
// Vérifier les logs PayTech
tail -f /var/logs/paytech.log

// Vérifier les logs PrestaShop
tail -f /var/logs/prestashop.log
```

### Mode debug
```php
// Activer le mode debug PayTech
// Dans la configuration du module
'debug_mode' => true,

// Les logs détaillés apparaîtront dans /var/logs/paytech_debug.log
```

## 🔄 Mises à jour

### Notifications automatiques
Le module vérifie automatiquement les mises à jour et affiche une notification dans le back-office.

### Processus de mise à jour
1. **Sauvegardez** votre boutique
2. **Téléchargez** la nouvelle version
3. **Remplacez** les fichiers du module
4. **Videz** le cache PrestaShop
5. **Testez** les paiements

### Migration des données
Le module préserve automatiquement :
- Configuration existante
- Historique des transactions
- Paramètres personnalisés

## 🌍 Multi-boutique

### Configuration multi-boutique
Pour les installations PrestaShop multi-boutiques :

1. **Sélectionnez** la boutique dans le contexte
2. **Configurez** PayTech pour chaque boutique
3. **Utilisez** des clés API différentes si nécessaire

### Gestion centralisée
```php
// Configuration partagée entre boutiques
'shared_config' => [
    'api_keys' => false,  // Clés séparées par boutique
    'settings' => true,   // Paramètres partagés
    'logs' => true        // Logs centralisés
]
```

## 📞 Support

### Ressources
- **Documentation** : [https://docs.paytech.sn/#/cms-prestashop](https://docs.paytech.sn/#/cms-prestashop)
- **Support PrestaShop** : [support@paytech.sn](mailto:support@paytech.sn)
- **Forum** : [https://www.prestashop.com/forums/](https://www.prestashop.com/forums/)

### Informations système
Pour le support, fournissez :
- Version PrestaShop
- Version du module PayTech
- Logs d'erreur
- Configuration utilisée
- Étapes pour reproduire le problème

## 📋 Compatibilité

### Versions PrestaShop supportées
- **PrestaShop 1.7.x** : ✅ Supporté
- **PrestaShop 8.x** : ✅ Supporté
- **PrestaShop 1.6.x** : ⚠️ Support limité

### Modules compatibles
- **Modules de livraison** : Tous compatibles
- **Modules de facturation** : Tous compatibles
- **Modules de stock** : Tous compatibles
- **Modules de marketing** : Compatibilité testée

### Thèmes compatibles
Le module PayTech est compatible avec tous les thèmes PrestaShop standards et la plupart des thèmes tiers.

## 📋 Checklist de déploiement

### Avant la mise en production

- [ ] Module installé et configuré
- [ ] Clés API de production renseignées
- [ ] Tests de paiement effectués
- [ ] Emails de confirmation testés
- [ ] Pages de succès/erreur vérifiées
- [ ] Sauvegarde de la boutique effectuée
- [ ] Formation équipe réalisée

### Après la mise en production

- [ ] Monitoring des transactions
- [ ] Vérification des IPN
- [ ] Tests réguliers
- [ ] Mises à jour du module
- [ ] Support client formé
- [ ] Rapports de réconciliation


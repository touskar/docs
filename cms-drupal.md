# CMS Drupal - Commerce PayTech

## Vue d'ensemble

Le module PayTech pour Drupal Commerce permet d'intégrer facilement les paiements PayTech dans votre boutique en ligne Drupal. Ce module gère automatiquement toutes les interactions avec l'API PayTech, y compris les notifications IPN, sans nécessiter de configuration manuelle complexe.

## Téléchargement

**Module Drupal Commerce PayTech v5.0.0**

[Télécharger le module]({{DOWNLOAD_URL}}/drupal/commerce_paytech.zip?v=5.0.0)

## Prérequis

Avant d'installer le module PayTech, assurez-vous que votre environnement Drupal répond aux exigences suivantes :

### Configuration système requise

- **Drupal** : Version 8.x ou 9.x
- **Drupal Commerce** : Version 2.x installée et configurée
- **PHP** : Version 7.4 ou supérieure
- **Extensions PHP** : cURL, JSON, OpenSSL
- **Base de données** : MySQL 5.7+ ou PostgreSQL 10+
- **Serveur web** : Apache 2.4+ ou Nginx 1.14+

### Modules Drupal requis

- Commerce Core
- Commerce Payment
- Commerce Order
- Commerce Product
- Commerce Cart

## Installation

### Étape 1 : Téléchargement et extraction

1. Téléchargez le fichier ZIP du module depuis le lien ci-dessus
2. Extrayez le contenu dans le répertoire `modules/contrib/` de votre installation Drupal
3. Le module doit être placé dans `modules/contrib/commerce_paytech/`

### Étape 2 : Installation via l'interface d'administration

1. Connectez-vous à votre interface d'administration Drupal
2. Naviguez vers **Extend** (Extensions)
3. Recherchez "PayTech" dans la liste des modules
4. Cochez la case à côté de **Commerce PayTech**
5. Cliquez sur **Install** pour activer le module

### Étape 3 : Installation via Drush (optionnel)

Si vous préférez utiliser Drush, exécutez les commandes suivantes :

```bash
# Activer le module
drush en commerce_paytech

# Vider le cache
drush cr
```

## Configuration

### Étape 1 : Configuration du mode de paiement

1. Naviguez vers **Commerce** → **Configuration** → **Payment** → **Payment gateways**
2. Cliquez sur **Add payment gateway**
3. Sélectionnez **PayTech** comme type de passerelle
4. Donnez un nom à votre passerelle (ex: "PayTech Sénégal")

### Étape 2 : Configuration des clés API

Dans la configuration de la passerelle PayTech, renseignez les informations suivantes :

#### Environnement de test
- **Clé publique** : Votre clé publique de test PayTech
- **Clé secrète** : Votre clé secrète de test PayTech
- **Mode** : Sélectionnez "Test"

#### Environnement de production
- **Clé publique** : Votre clé publique de production PayTech
- **Clé secrète** : Votre clé secrète de production PayTech
- **Mode** : Sélectionnez "Production"

### Étape 3 : Configuration des URLs de retour

Le module configure automatiquement les URLs de retour suivantes :

- **URL de succès** : `votre-site.com/checkout/[order-id]/payment/return/[step-id]/[payment-gateway-id]`
- **URL d'annulation** : `votre-site.com/checkout/[order-id]/payment/cancel/[step-id]/[payment-gateway-id]`
- **URL IPN** : `votre-site.com/payment/notify/paytech`

### Étape 4 : Configuration des méthodes de paiement

Vous pouvez configurer quelles méthodes de paiement PayTech sont disponibles :

- Orange Money
- Tigo Cash
- Wave
- PayExpresse
- Carte Bancaire
- Wari
- Poste Cash
- PayPal
- Emoney
- Joni Joni

## Fonctionnalités

### Gestion automatique des IPN

Le module PayTech pour Drupal gère automatiquement les notifications IPN (Instant Payment Notification) de PayTech. Lorsqu'un paiement est effectué, PayTech envoie une notification à votre site pour confirmer le statut de la transaction.

#### Traitement automatique

- **Validation des signatures** : Le module vérifie automatiquement la signature HMAC des notifications IPN
- **Mise à jour des commandes** : Les statuts des commandes sont mis à jour automatiquement selon le résultat du paiement
- **Gestion des erreurs** : Les erreurs de paiement sont traitées et loggées automatiquement
- **Prévention des doublons** : Le module empêche le traitement multiple des mêmes notifications

#### Statuts de commande

Le module met à jour les statuts de commande selon les résultats PayTech :

- **Paiement réussi** : La commande passe au statut "Payée"
- **Paiement échoué** : La commande reste au statut "En attente" avec une note d'erreur
- **Paiement annulé** : La commande passe au statut "Annulée"

### Support mobile et préfillage

Le module supporte les intégrations mobiles avec préfillage automatique des informations utilisateur :

#### Paramètres de préfillage

- **Numéro de téléphone** : Préfillage automatique du numéro de l'utilisateur
- **Nom complet** : Préfillage du nom et prénom de l'utilisateur
- **Méthode de paiement** : Redirection directe vers une méthode spécifique si configurée
- **Auto-submit** : Soumission automatique du formulaire de paiement

#### Configuration mobile

Pour les applications mobiles utilisant des webviews, le module utilise les URLs spéciales PayTech :

- **URL de succès mobile** : `https://paytech.sn/mobile/success`
- **URL d'annulation mobile** : `https://paytech.sn/mobile/cancel`

## Personnalisation

### Hooks Drupal disponibles

Le module fournit plusieurs hooks pour personnaliser le comportement :

#### hook_commerce_paytech_payment_alter

Permet de modifier les données de paiement avant l'envoi à PayTech :

```php
/**
 * Implements hook_commerce_paytech_payment_alter().
 */
function mymodule_commerce_paytech_payment_alter(&$payment_data, $order) {
  // Ajouter des champs personnalisés
  $payment_data['custom_field']['customer_id'] = $order->getCustomerId();
  
  // Modifier la description
  $payment_data['item_name'] = 'Commande #' . $order->getOrderNumber();
}
```

#### hook_commerce_paytech_ipn_received

Permet d'effectuer des actions personnalisées lors de la réception d'une IPN :

```php
/**
 * Implements hook_commerce_paytech_ipn_received().
 */
function mymodule_commerce_paytech_ipn_received($ipn_data, $order) {
  // Logger des informations personnalisées
  \Drupal::logger('mymodule')->info('Paiement PayTech reçu pour la commande @order', [
    '@order' => $order->getOrderNumber()
  ]);
  
  // Envoyer un email personnalisé
  if ($ipn_data['state'] === 'success') {
    // Logique d'envoi d'email
  }
}
```

### Templates Twig personnalisables

Le module fournit des templates Twig que vous pouvez surcharger :

#### commerce-paytech-payment-form.html.twig

Template pour le formulaire de paiement :

```twig
<div class="paytech-payment-form">
  <h3>{{ 'Paiement sécurisé avec PayTech'|t }}</h3>
  
  <div class="payment-methods">
    {% for method in payment_methods %}
      <div class="payment-method">
        <input type="radio" name="payment_method" value="{{ method.id }}" id="method-{{ method.id }}">
        <label for="method-{{ method.id }}">
          <img src="{{ method.logo }}" alt="{{ method.name }}">
          {{ method.name }}
        </label>
      </div>
    {% endfor %}
  </div>
  
  {{ form }}
</div>
```

## Sécurité

### Validation des signatures HMAC

Le module implémente une validation stricte des signatures HMAC pour toutes les notifications IPN :

```php
private function validateHmacSignature($data, $signature) {
  $message = $data['amount'] . '|' . $data['id_transaction'] . '|' . $this->publicKey;
  $expected_signature = hash_hmac('sha256', $message, $this->secretKey);
  
  return hash_equals($expected_signature, $signature);
}
```

### Protection CSRF

Les endpoints IPN sont automatiquement exemptés de la protection CSRF de Drupal pour permettre la réception des notifications PayTech.

### Logging et audit

Toutes les transactions et notifications sont loggées dans les logs Drupal :

- **Tentatives de paiement** : Enregistrement de toutes les demandes de paiement
- **Notifications IPN** : Logging de toutes les notifications reçues
- **Erreurs de validation** : Enregistrement des erreurs de signature ou de données
- **Changements de statut** : Audit des modifications de statut de commande

## Dépannage

### Problèmes courants

#### Les notifications IPN ne sont pas reçues

1. Vérifiez que l'URL IPN est accessible publiquement
2. Assurez-vous que le module est activé
3. Vérifiez les logs Drupal pour les erreurs
4. Testez l'URL IPN manuellement avec un outil comme Postman

#### Les paiements restent en attente

1. Vérifiez la configuration des clés API
2. Assurez-vous que l'environnement (test/production) est correct
3. Vérifiez les logs PayTech dans votre tableau de bord
4. Testez avec un paiement de faible montant

#### Erreurs de signature HMAC

1. Vérifiez que les clés API sont correctement configurées
2. Assurez-vous qu'il n'y a pas d'espaces supplémentaires dans les clés
3. Vérifiez que l'environnement correspond aux clés utilisées

### Logs et débogage

Pour activer le débogage détaillé :

1. Naviguez vers **Configuration** → **Development** → **Logging and errors**
2. Définissez le niveau de log sur "All messages"
3. Consultez les logs dans **Reports** → **Recent log messages**

### Support technique

Pour obtenir de l'aide supplémentaire :

- **Documentation officielle** : [https://docs.paytech.sn](https://docs.paytech.sn)
- **Support PayTech** : support@paytech.sn
- **Issues GitHub** : Reportez les bugs sur le dépôt du module

## Mise à jour

### Processus de mise à jour

1. Sauvegardez votre site et base de données
2. Téléchargez la nouvelle version du module
3. Remplacez les fichiers du module dans `modules/contrib/commerce_paytech/`
4. Exécutez les mises à jour de base de données : `drush updb`
5. Videz le cache : `drush cr`

### Notes de version

#### Version 5.0.0
- Support de Drupal 9.x
- Amélioration de la validation HMAC
- Nouvelles méthodes de paiement (Joni Joni, Emoney)
- Interface d'administration améliorée
- Correction de bugs de compatibilité

## Bonnes pratiques

### Performance

- Activez le cache Drupal pour améliorer les performances
- Utilisez un CDN pour servir les assets statiques
- Optimisez la base de données régulièrement

### Sécurité

- Gardez Drupal et tous les modules à jour
- Utilisez HTTPS pour toutes les transactions
- Surveillez les logs pour détecter les activités suspectes
- Sauvegardez régulièrement votre site

### Monitoring

- Configurez des alertes pour les échecs de paiement
- Surveillez les performances des pages de checkout
- Testez régulièrement le processus de paiement complet

Le module PayTech pour Drupal Commerce offre une intégration complète et sécurisée des paiements PayTech dans votre boutique en ligne Drupal, avec une gestion automatique des notifications IPN et un support complet pour les intégrations mobiles.


# Laravel - Intégration PayTech

Cette section détaille l'intégration de PayTech dans une application Laravel en utilisant le package officiel open source.

## Package officiel Laravel PayTech

PayTech propose un package Laravel officiel qui simplifie grandement l'intégration. Ce package gère automatiquement les paiements, les notifications IPN, et fournit une interface complète.

**Dépôt GitHub** : [https://github.com/touskar/laravel-paytech](https://github.com/touskar/laravel-paytech)

## Installation

### Via Composer

Installez le package via Composer :

```bash
composer require babaly/laravel-paytech
```

### Configuration du service provider

Ajoutez le service provider dans `config/app.php` :

```php
return [
    'providers' => [
        // ...
        App\Providers\PaytechServiceProvider::class
    ]
];
```

### Publication des ressources

Vous pouvez publier toutes les ressources en une fois :

```bash
php artisan vendor:publish --provider="Babaly\LaravelPaytech\LaravelPaytechServiceProvider"
```

Ou publier sélectivement selon vos besoins :

#### Migrations
```bash
php artisan vendor:publish --tag="laravel-paytech-migrations"
php artisan migrate
```

#### Configuration
```bash
php artisan vendor:publish --tag="laravel-paytech-config"
```

#### Vues
```bash
php artisan vendor:publish --tag="laravel-paytech-views"
```

#### Services
```bash
php artisan vendor:publish --tag="laravel-paytech-services"
```

#### Modèles
```bash
php artisan vendor:publish --tag="laravel-paytech-models"
```

#### Contrôleurs
```bash
php artisan vendor:publish --tag="laravel-paytech-controllers"
```

## Configuration

### Fichier de configuration

Le package crée automatiquement le fichier `config/paytech.php` :

```php
return [
    'PAYTECH_API_KEY' => env('PAYTECH_API_KEY', ''),
    'PAYTECH_SECRET_KEY' => env('PAYTECH_SECRET_KEY', ''),
];
```

### Variables d'environnement

Ajoutez vos clés PayTech dans le fichier `.env` :

```env
# PayTech Configuration
PAYTECH_API_KEY=votre_api_key_publique
PAYTECH_SECRET_KEY=votre_api_secret
```

## Utilisation

### Routes automatiques

Le package configure automatiquement les routes suivantes :

```php
use App\Http\Controllers\PaymentController;

Route::get('payment', [PaymentController::class, 'index'])->name('payment.index');
Route::post('/checkout', [PaymentController::class, 'payment'])->name('payment.submit');
Route::get('ipn', [PaymentController::class, 'ipn'])->name('paytech-ipn');
Route::get('payment-success/{code}', [PaymentController::class, 'success'])->name('payment.success');
Route::get('payment/{code}/success', [PaymentController::class, 'paymentSuccessView'])->name('payment.success.view');
Route::get('payment-cancel', [PaymentController::class, 'cancel'])->name('paytech.cancel');
```

### Démarrage rapide

1. **Lancez votre application** :
```bash
php artisan serve
```

2. **Accédez à la page de paiement** :
```
http://127.0.0.1:8000/payment
```

3. **Testez un paiement** en cliquant sur le bouton de validation

## Interface utilisateur

### Page de paiement

Le package fournit une interface de paiement complète avec :

- **Formulaire de paiement** avec validation
- **Sélection des méthodes** de paiement PayTech
- **Préfillage automatique** des informations utilisateur
- **Interface responsive** pour mobile et desktop

### Redirection PayTech

Après validation, l'utilisateur est redirigé vers la plateforme PayTech avec toutes les méthodes de paiement disponibles.

### Support mobile

Le package supporte les intégrations mobiles avec :

- **URLs spéciales** pour Flutter/React Native
- **Préfillage automatique** des données utilisateur
- **Gestion des webviews** mobiles
- **Callbacks** pour les applications natives

## Fonctionnalités avancées

### Gestion automatique des IPN

Le package gère automatiquement :

- **Réception des notifications** PayTech
- **Validation des signatures** HMAC et SHA256
- **Mise à jour des statuts** de commande
- **Logging** des transactions
- **Gestion des erreurs** et doublons

### Modes de fonctionnement

#### Mode test (par défaut)

En mode test, PayTech ne débite que 100 FCFA quel que soit le montant de la transaction, permettant de tester l'intégration sans frais réels.

#### Mode production

Pour passer en mode production, utilisez les méthodes du contrôleur :

```php
// Désactiver le mode test
$paymentController->setTestMode(false);

// Ou activer le mode live directement
$paymentController->setLiveMode(true);
```

### Personnalisation

#### Contrôleur personnalisé

Vous pouvez étendre le contrôleur de base pour ajouter vos propres fonctionnalités :

```php
<?php

namespace App\Http\Controllers;

use Babaly\LaravelPaytech\Http\Controllers\PaymentController as BasePaymentController;

class CustomPaymentController extends BasePaymentController
{
    /**
     * Logique personnalisée après paiement réussi
     */
    protected function onPaymentSuccess($transaction)
    {
        // Envoyer un email de confirmation
        // Mettre à jour les stocks
        // Déclencher des webhooks
        
        parent::onPaymentSuccess($transaction);
    }

    /**
     * Logique personnalisée après échec de paiement
     */
    protected function onPaymentFailure($transaction)
    {
        // Logger l'échec
        // Notifier l'administrateur
        // Proposer des alternatives
        
        parent::onPaymentFailure($transaction);
    }
}
```

#### Configuration avancée

Étendez la configuration dans `config/paytech.php` :

```php
return [
    'PAYTECH_API_KEY' => env('PAYTECH_API_KEY', ''),
    'PAYTECH_SECRET_KEY' => env('PAYTECH_SECRET_KEY', ''),
    
    // Configuration personnalisée
    'currency' => env('PAYTECH_CURRENCY', 'XOF'),
    'environment' => env('PAYTECH_ENV', 'test'),
    'timeout' => env('PAYTECH_TIMEOUT', 30),
    
    'urls' => [
        'success' => env('PAYTECH_SUCCESS_URL'),
        'cancel' => env('PAYTECH_CANCEL_URL'),
        'ipn' => env('PAYTECH_IPN_URL'),
    ],
    
    'mobile' => [
        'success_url' => 'https://paytech.sn/mobile/success',
        'cancel_url' => 'https://paytech.sn/mobile/cancel',
    ],
    
    'payment_methods' => [
        'Orange Money',
        'Tigo Cash',
        'Wave',
        'PayExpresse',
        'Carte Bancaire',
        'Wari',
        'Poste Cash',
        'PayPal',
        'Emoney',
        'Joni Joni'
    ],
];
```

## Intégration avec préfillage

### Préfillage utilisateur automatique

Le package supporte le préfillage automatique des informations utilisateur :

```php
// Dans votre contrôleur
public function initiatePayment(Request $request)
{
    $paymentData = [
        'item_name' => 'iPhone 13 Pro',
        'item_price' => 650000,
        'ref_command' => 'CMD_' . time(),
        
        // Données utilisateur pour préfillage
        'user' => [
            'phone_number' => '+221771234567',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'auto_submit' => true
        ],
        
        // Méthode de paiement ciblée (optionnel)
        'payment_method' => 'Orange Money',
        
        // Données personnalisées
        'custom_field' => [
            'user_id' => auth()->id(),
            'source' => 'laravel_app'
        ]
    ];
    
    return $this->processPayment($paymentData);
}
```

### Paramètres de redirection

Le package ajoute automatiquement les paramètres de préfillage à l'URL de redirection :

- `pn` : Numéro complet avec indicatif (+221771234567)
- `nn` : Numéro national (771234567)
- `fn` : Nom complet (John Doe)
- `tp` : Méthode de paiement ciblée
- `nac` : Auto-submit (1=oui, 0=non)

## API et webhooks

### Endpoints disponibles

Le package expose plusieurs endpoints :

```php
// Vérifier le statut d'une transaction
GET /api/paytech/transaction/{ref_command}/status

// Relancer une notification IPN
POST /api/paytech/transaction/{ref_command}/retry-ipn

// Obtenir l'historique des transactions
GET /api/paytech/transactions?user_id={id}
```

### Événements Laravel

Le package déclenche des événements Laravel :

```php
// Écouter les événements de paiement
Event::listen('paytech.payment.success', function ($transaction) {
    // Logique après paiement réussi
});

Event::listen('paytech.payment.failed', function ($transaction) {
    // Logique après échec de paiement
});

Event::listen('paytech.ipn.received', function ($ipnData) {
    // Logique après réception IPN
});
```

## Tests et débogage

### Mode test

Le package est configuré en mode test par défaut. En mode test :

- Seuls 100 FCFA sont débités quel que soit le montant
- Toutes les méthodes de paiement sont disponibles
- Les notifications IPN fonctionnent normalement

### Logs

Le package log automatiquement :

- Toutes les demandes de paiement
- Les réponses de l'API PayTech
- Les notifications IPN reçues
- Les erreurs et exceptions

Consultez les logs dans `storage/logs/laravel.log`.

### Commandes Artisan

Le package fournit des commandes utiles :

```bash
# Vérifier la configuration PayTech
php artisan paytech:config

# Tester la connectivité API
php artisan paytech:test-connection

# Nettoyer les transactions expirées
php artisan paytech:cleanup-transactions

# Synchroniser les statuts avec PayTech
php artisan paytech:sync-transactions
```

## Migration vers la production

### Checklist de production

1. **Obtenir les clés de production** depuis votre dashboard PayTech
2. **Mettre à jour les variables d'environnement** :
```env
PAYTECH_API_KEY=votre_cle_production
PAYTECH_SECRET_KEY=votre_secret_production
```
3. **Désactiver le mode test** dans votre code :
```php
$paymentController->setLiveMode(true);
```
4. **Configurer les URLs de production** pour IPN et callbacks
5. **Tester avec de petits montants** avant le lancement
6. **Configurer le monitoring** et les alertes

### Sécurité en production

- Utilisez HTTPS pour toutes les URLs
- Configurez des timeouts appropriés
- Implémentez un rate limiting
- Surveillez les logs d'erreur
- Sauvegardez régulièrement les données de transaction

## Support et documentation

- **Documentation officielle** : [https://doc.paytech.sn/](https://doc.paytech.sn/)
- **Dépôt GitHub** : [https://github.com/touskar/laravel-paytech](https://github.com/touskar/laravel-paytech)
- **Issues et bugs** : Reportez sur GitHub
- **Support PayTech** : support@paytech.sn

Le package Laravel PayTech officiel simplifie considérablement l'intégration en fournissant une solution clé en main avec toutes les fonctionnalités nécessaires pour accepter des paiements PayTech dans vos applications Laravel.


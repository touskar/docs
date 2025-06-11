# WordPress/WooCommerce

Guide d'intégration PayTech pour WordPress et WooCommerce.

## 📦 Installation

### Téléchargement

**PayTech WooCommerce v6.0.3** - Mise à jour du 07 Avril 2025 12H02  
[Télécharger paytech_woocommerce.zip]({{DOWNLOAD_URL}}/woocomerce/paytech_woocommerce.zip?v=6.0.3)

### Méthode 1 : Via l'admin WordPress

1. **Téléchargez** le plugin depuis le lien ci-dessus
2. **Connectez-vous** à votre admin WordPress
3. **Allez** dans Extensions > Ajouter
4. **Cliquez** sur "Téléverser une extension"
5. **Sélectionnez** le fichier `paytech_woocommerce.zip`
6. **Cliquez** sur "Installer maintenant"
7. **Activez** l'extension

### Méthode 2 : Via FTP

1. **Téléchargez** et **décompressez** le plugin
2. **Uploadez** le dossier `paytech-woocommerce` dans `/wp-content/plugins/`
3. **Activez** le plugin depuis l'admin WordPress

## ⚙️ Configuration

> ✅ **Important** : Le plugin PayTech WooCommerce gère **automatiquement** les notifications IPN. Vous n'avez pas besoin de configurer manuellement les webhooks ou les endpoints IPN. Il suffit d'installer le plugin et de renseigner vos clés API.

### 1. Accès aux paramètres

1. **Allez** dans WooCommerce > Réglages
2. **Cliquez** sur l'onglet "Paiements"
3. **Trouvez** "PayTech" dans la liste
4. **Cliquez** sur "Gérer"

### 2. Configuration de base

#### Paramètres généraux
- **Activer PayTech** : Cochez pour activer
- **Titre** : "Paiement mobile et carte bancaire"
- **Description** : "Payez avec Orange Money, Tigo Cash, Wave ou carte bancaire"

#### Clés API
- **API Key** : Votre clé publique PayTech
- **API Secret** : Votre clé secrète PayTech
- **Environnement** : Test ou Production

#### URLs de retour
- **URL de succès** : `https://votre-site.com/commande-recue/`
- **URL d'annulation** : `https://votre-site.com/panier/`
- **URL IPN** : `https://votre-site.com/wp-json/paytech/v1/ipn`

### 3. Configuration avancée

#### Méthodes de paiement
```php
// Personnaliser les méthodes affichées
add_filter('paytech_payment_methods', function($methods) {
    return [
        'Orange Money',
        'Tigo Cash',
        'Wave',
        'Carte Bancaire'
    ];
});
```

#### Devises supportées
- **XOF** (Franc CFA) - par défaut
- **EUR** (Euro)
- **USD** (Dollar US)

## 🎨 Personnalisation

### Apparence du bouton

```css
/* Personnaliser le bouton PayTech */
.woocommerce .paytech-button {
    background: #135571;
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.woocommerce .paytech-button:hover {
    background: #2c5aa0;
}
```

### Logo personnalisé

```php
// Ajouter un logo personnalisé
add_filter('paytech_gateway_icon', function($icon_url) {
    return 'https://votre-site.com/logo-paytech.png';
});
```

## 🔧 Hooks et filtres

### Filtres disponibles

#### Modifier les données de paiement
```php
add_filter('paytech_payment_data', function($data, $order) {
    // Ajouter des champs personnalisés
    $data['custom_field'] = json_encode([
        'customer_id' => $order->get_customer_id(),
        'order_notes' => $order->get_customer_note()
    ]);
    
    return $data;
}, 10, 2);
```

#### Personnaliser l'URL de retour
```php
add_filter('paytech_success_url', function($url, $order) {
    return add_query_arg([
        'order_id' => $order->get_id(),
        'key' => $order->get_order_key()
    ], $url);
}, 10, 2);
```

### Actions disponibles

#### Après paiement réussi
```php
add_action('paytech_payment_complete', function($order_id, $paytech_data) {
    $order = wc_get_order($order_id);
    
    // Logique personnalisée après paiement
    // Envoyer un email, mettre à jour un CRM, etc.
    
}, 10, 2);
```

#### Après paiement échoué
```php
add_action('paytech_payment_failed', function($order_id, $error_message) {
    $order = wc_get_order($order_id);
    
    // Logique en cas d'échec
    $order->add_order_note('Paiement PayTech échoué: ' . $error_message);
    
}, 10, 2);
```

## 📱 Support mobile

### Détection automatique
Le plugin détecte automatiquement les appareils mobiles et adapte l'interface :

```php
// Vérifier si c'est un appareil mobile
if (wp_is_mobile()) {
    // Interface mobile optimisée
    $success_url = 'https://paytech.sn/mobile/success';
    $cancel_url = 'https://paytech.sn/mobile/cancel';
}
```

### Deep linking
Pour les applications mobiles, configurez les deep links :

```php
add_filter('paytech_mobile_config', function($config) {
    $config['deep_link_success'] = 'monapp://payment/success';
    $config['deep_link_cancel'] = 'monapp://payment/cancel';
    return $config;
});
```

## 🔔 Notifications IPN

### Configuration automatique
Le plugin configure automatiquement l'endpoint IPN :
- **URL** : `https://votre-site.com/wp-json/paytech/v1/ipn`
- **Méthode** : POST
- **Format** : form-data

### Traitement personnalisé
```php
add_action('paytech_ipn_received', function($ipn_data) {
    // Traitement personnalisé des IPN
    error_log('IPN PayTech reçu: ' . print_r($ipn_data, true));
    
    // Vérifications supplémentaires
    if ($ipn_data['type_event'] === 'sale_complete') {
        // Paiement confirmé
        do_action('mon_paiement_confirme', $ipn_data);
    }
});
```

## 🛡️ Sécurité

### Validation des IPN
```php
// Le plugin valide automatiquement les IPN
add_filter('paytech_validate_ipn', function($is_valid, $ipn_data) {
    // Validation supplémentaire si nécessaire
    return $is_valid && custom_validation($ipn_data);
}, 10, 2);
```

### Logs de sécurité
```php
// Activer les logs détaillés
add_filter('paytech_enable_logging', '__return_true');

// Personnaliser le niveau de log
add_filter('paytech_log_level', function() {
    return 'debug'; // debug, info, warning, error
});
```

## 🧪 Mode test

### Activation du mode test
1. **Paramètres PayTech** > Environnement > "Test"
2. **Utilisez** vos clés API de test
3. **Testez** avec les cartes de test

### Cartes de test
```
Visa Test: 4111111111111111
Expiry: 12/25
CVV: 123

Mastercard Test: 5555555555554444
Expiry: 12/25
CVV: 123
```

### Numéros de test mobile
```
Orange Money Test: +221701234567
Tigo Cash Test: +221761234567
Wave Test: +221781234567
```

## 📊 Rapports et analytics

### Dashboard WooCommerce
Le plugin ajoute des métriques PayTech au dashboard :
- Transactions PayTech
- Méthodes de paiement populaires
- Taux de conversion

### Exports
```php
// Exporter les transactions PayTech
add_action('admin_init', function() {
    if (isset($_GET['export_paytech'])) {
        $transactions = get_paytech_transactions();
        export_to_csv($transactions);
    }
});
```

## 🔧 Dépannage

### Problèmes courants

#### 1. IPN non reçus
- Vérifiez que l'URL IPN est accessible
- Désactivez les plugins de cache pour l'endpoint IPN
- Vérifiez les logs WordPress

#### 2. Redirections échouées
- Vérifiez les URLs de retour
- Assurez-vous que HTTPS est activé
- Vérifiez les permaliens WordPress

#### 3. Erreurs de validation
```php
// Debug des erreurs PayTech
add_action('paytech_error', function($error_message, $error_code) {
    error_log("Erreur PayTech [$error_code]: $error_message");
}, 10, 2);
```

### Logs de débogage
```php
// Activer les logs détaillés
define('PAYTECH_DEBUG', true);

// Voir les logs
tail -f /wp-content/debug.log | grep PayTech
```

## 🔄 Mises à jour

### Notifications automatiques
Le plugin vérifie automatiquement les mises à jour et affiche une notification dans l'admin WordPress.

### Migration des données
```php
// Hook de migration lors des mises à jour
add_action('paytech_plugin_updated', function($old_version, $new_version) {
    if (version_compare($old_version, '6.0.0', '<')) {
        // Migration spécifique pour v6.0.0
        migrate_paytech_settings();
    }
}, 10, 2);
```

## 📞 Support

### Ressources
- **Documentation** : [https://docs.paytech.sn/#/cms-wordpress](https://docs.paytech.sn/#/cms-wordpress)
- **Support WordPress** : [support@paytech.sn](mailto:support@paytech.sn)
- **Forum** : [https://wordpress.org/support/plugin/paytech-woocommerce](https://wordpress.org/support/plugin/paytech-woocommerce)

### Informations système
```php
// Afficher les informations système pour le support
add_action('admin_menu', function() {
    add_submenu_page(
        'woocommerce',
        'PayTech System Info',
        'PayTech Info',
        'manage_options',
        'paytech-system-info',
        'paytech_system_info_page'
    );
});
```

## 📋 Checklist de déploiement

### Avant la mise en production

- [ ] Plugin installé et activé
- [ ] Clés API de production configurées
- [ ] URLs de retour testées
- [ ] IPN fonctionnels
- [ ] Tests de paiement effectués
- [ ] Logs de sécurité activés
- [ ] Sauvegarde de la base de données
- [ ] Documentation équipe fournie

### Après la mise en production

- [ ] Monitoring des transactions
- [ ] Vérification des IPN
- [ ] Tests réguliers
- [ ] Mises à jour du plugin
- [ ] Support client formé


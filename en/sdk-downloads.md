# PayTech SDKs and Downloads

This page brings together all official PayTech SDKs, libraries and development tools to facilitate integration across different platforms.

## 📦 Official Repositories

All files are available and maintained by the PayTech team. They are regularly updated to ensure compatibility with the latest platform versions.

### 🛒 E-commerce and CMS

#### WordPress - WooCommerce
- **Version**: PayTech 6.0.3
- **Last Update**: April 07, 2025 12:02 PM
- **Support**: WooCommerce update support
- **Download**: [paytech_woocommerce.zip](https://doc.paytech.sn/downloads/sdk/woocomerce/paytech_woocommerce.zip?v=6.0.3)

#### Drupal Commerce
- **Version**: 5.0.0
- **Download**: [commerce_paytech.zip](https://doc.paytech.sn/downloads/sdk/drupal/commerce_paytech.zip?v=5.0.0)

#### PrestaShop
- **Version**: 1.0
- **Download**: [paytech.zip](https://doc.paytech.sn/downloads/sdk/prestashop/paytech.zip?v=1)

### 💻 Server SDKs

#### PHP
- **Description**: Complete PHP SDK for server integration
- **Download**: [paytech_php.zip](https://doc.paytech.sn/downloads/sdk/paytech_php.zip)
- **Usage**: Server-side integration, payment and IPN management

### 📱 Mobile SDKs

#### Android
- **Description**: Library & Android test project
- **Download**: [paytech_android.zip](https://doc.paytech.sn/downloads/sdk/paytech_android.zip)
- **Contents**: 
  - PayTech Android library (.aar)
  - Complete example project
  - Integration documentation

#### iOS
- **Description**: iOS Library via CocoaPods
- **Installation**: [CocoaPods - Paytech](https://cocoapods.org/pods/Paytech)
- **Command**: `pod 'Paytech'`

### 🧪 Testing Tools

#### Demo Store
- **Description**: Complete test application
- **Download**: [shop_test.zip](https://doc.paytech.sn/downloads/sdk/shop_test.zip)
- **Usage**: Integration testing, implementation examples

## 📋 Installation Guide

### WordPress/WooCommerce

1. **Download** the PayTech WooCommerce plugin
2. **Install** via WordPress admin interface
3. **Activate** the plugin in the extensions list
4. **Configure** your API keys in WooCommerce > Settings > Payments

### Drupal Commerce

1. **Download** the PayTech Commerce module
2. **Extract** to Drupal modules folder
3. **Enable** the module via admin interface
4. **Configure** payment settings

### PrestaShop

1. **Download** the PayTech PrestaShop module
2. **Install** via module manager
3. **Configure** PayTech settings
4. **Test** in sandbox mode

### PHP SDK

```php
<?php
// Include PayTech SDK
require_once 'paytech_php/PayTech.php';

// Configuration
$paytech = new PayTech([
    'api_key' => 'your_api_key',
    'api_secret' => 'your_api_secret',
    'env' => 'test' // or 'prod'
]);

// Payment request
$response = $paytech->requestPayment([
    'item_name' => 'Test product',
    'item_price' => 1000,
    'currency' => 'XOF',
    'ref_command' => 'CMD_' . time()
]);
?>
```

### Android SDK

```java
// Add dependency in build.gradle
implementation files('libs/paytech-sdk.aar')

// Usage in your Activity
PayTech paytech = new PayTech(this);
paytech.setApiKey("your_api_key");
paytech.setApiSecret("your_api_secret");
paytech.setEnvironment(PayTech.Environment.TEST);

// Start payment
PaymentRequest request = new PaymentRequest()
    .setItemName("Test product")
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

// Usage in your project
import Paytech

let paytech = PayTech()
paytech.apiKey = "your_api_key"
paytech.apiSecret = "your_api_secret"
paytech.environment = .test

// Payment configuration
let request = PaymentRequest()
request.itemName = "Test product"
request.itemPrice = 1000
request.currency = "XOF"
request.refCommand = "CMD_\(Date().timeIntervalSince1970)"

paytech.startPayment(request: request)
```

## 🔧 System Requirements

### Server
- **PHP**: Version 7.4 or higher
- **HTTPS**: Required for production
- **Extensions**: cURL, JSON, OpenSSL

### WordPress/WooCommerce
- **WordPress**: Version 5.0 or higher
- **WooCommerce**: Version 4.0 or higher
- **PHP**: Version 7.4 or higher

### Android
- **API Level**: 21 (Android 5.0) or higher
- **Permissions**: INTERNET, ACCESS_NETWORK_STATE

### iOS
- **iOS**: Version 11.0 or higher
- **Xcode**: Version 12.0 or higher

## 📚 Additional Documentation

### Integration Guides
- [Detailed PHP Guide](server-php.md)
- [Complete Android Guide](mobile-android.md)
- [Detailed iOS Guide](mobile-ios.md)
- [WooCommerce Configuration](cms-wordpress.md)

### Development Resources
- [API Reference](payment-request.md)
- [Error Handling](error-codes.md)
- [IPN Notifications](ipn-how-it-works.md)
- [Testing and Debugging](testing-debug.md)

## 🆘 Technical Support

### Installation Issues
- Check system requirements
- Review error logs
- Test in sandbox mode

### Support Contact
- **Email**: [support@paytech.sn](mailto:support@paytech.sn)
- **Phone**: +221 77 245 71 99
- **Documentation**: [doc.paytech.sn](https://doc.paytech.sn)

---

> 💡 **Tip**: Always start by testing with the demo store before integrating into your production environment. This will help you understand the payment flow and validate your configuration.


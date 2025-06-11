# Intégration Flutter

PayTech propose un SDK Flutter complet pour intégrer facilement les paiements dans vos applications mobiles cross-platform. Cette section détaille l'installation, la configuration et l'utilisation du SDK Flutter PayTech.

## Installation

### Ajout de la dépendance

Ajoutez le package PayTech à votre fichier `pubspec.yaml` :

```yaml
dependencies:
  flutter:
    sdk: flutter
  paytech_flutter: ^2.1.0
  # Autres dépendances...

dev_dependencies:
  flutter_test:
    sdk: flutter
```

### Installation du package

```bash
flutter pub get
```

### Configuration Android

Ajoutez les permissions nécessaires dans `android/app/src/main/AndroidManifest.xml` :

```xml
<manifest xmlns:android="http://schemas.android.com/apk/res/android"
    package="com.example.votre_app">
    
    <!-- Permissions PayTech -->
    <uses-permission android:name="android.permission.INTERNET" />
    <uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
    
    <application
        android:label="votre_app"
        android:icon="@mipmap/ic_launcher">
        
        <!-- Configuration PayTech -->
        <meta-data
            android:name="com.paytech.API_KEY"
            android:value="@string/paytech_api_key" />
        <meta-data
            android:name="com.paytech.API_SECRET"
            android:value="@string/paytech_api_secret" />
            
        <!-- Activité principale -->
        <activity
            android:name=".MainActivity"
            android:exported="true"
            android:launchMode="singleTop"
            android:theme="@style/LaunchTheme">
            <!-- Intent filters... -->
        </activity>
    </application>
</manifest>
```

Ajoutez vos clés dans `android/app/src/main/res/values/strings.xml` :

```xml
<resources>
    <string name="app_name">Votre App</string>
    <string name="paytech_api_key">votre_api_key_ici</string>
    <string name="paytech_api_secret">votre_api_secret_ici</string>
</resources>
```

### Configuration iOS

Ajoutez les configurations dans `ios/Runner/Info.plist` :

```xml
<dict>
    <!-- Autres configurations... -->
    
    <!-- Configuration PayTech -->
    <key>PayTechAPIKey</key>
    <string>votre_api_key_ici</string>
    <key>PayTechAPISecret</key>
    <string>votre_api_secret_ici</string>
    
    <!-- Permissions réseau -->
    <key>NSAppTransportSecurity</key>
    <dict>
        <key>NSAllowsArbitraryLoads</key>
        <false/>
        <key>NSExceptionDomains</key>
        <dict>
            <key>paytech.sn</key>
            <dict>
                <key>NSExceptionAllowsInsecureHTTPLoads</key>
                <false/>
                <key>NSExceptionMinimumTLSVersion</key>
                <string>TLSv1.2</string>
            </dict>
        </dict>
    </dict>
</dict>
```

## Configuration de base

### Initialisation du SDK

```dart
import 'package:flutter/material.dart';
import 'package:paytech_flutter/paytech_flutter.dart';

class PayTechConfig {
  static const String apiKey = 'votre_api_key';
  static const String apiSecret = 'votre_api_secret';
  static const bool isProduction = false; // true pour la production
  
  static PayTech get instance {
    return PayTech(
      apiKey: apiKey,
      apiSecret: apiSecret,
      environment: isProduction ? Environment.production : Environment.test,
    );
  }
}

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'PayTech Demo',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: PaymentScreen(),
    );
  }
}
```

### Configuration avancée

```dart
class PayTechManager {
  static PayTech? _instance;
  
  static PayTech get instance {
    _instance ??= PayTech(
      apiKey: PayTechConfig.apiKey,
      apiSecret: PayTechConfig.apiSecret,
      environment: PayTechConfig.isProduction 
          ? Environment.production 
          : Environment.test,
      config: PayTechConfiguration(
        timeout: Duration(seconds: 30),
        retryAttempts: 3,
        enableLogging: !PayTechConfig.isProduction,
        customUserAgent: 'MonApp/1.0.0 Flutter',
      ),
    );
    return _instance!;
  }
  
  static void dispose() {
    _instance?.dispose();
    _instance = null;
  }
}
```

## Implémentation des paiements

### Paiement simple

```dart
class PaymentScreen extends StatefulWidget {
  @override
  _PaymentScreenState createState() => _PaymentScreenState();
}

class _PaymentScreenState extends State<PaymentScreen> {
  final PayTech paytech = PayTechManager.instance;
  bool isLoading = false;
  
  Future<void> makePayment() async {
    setState(() {
      isLoading = true;
    });
    
    try {
      final paymentRequest = PaymentRequest(
        itemName: 'iPhone 13 Pro',
        itemPrice: 650000,
        currency: Currency.XOF,
        refCommand: 'CMD_${DateTime.now().millisecondsSinceEpoch}',
        commandName: 'Achat iPhone 13 Pro 256GB',
        customField: {
          'user_id': '12345',
          'order_type': 'mobile_purchase'
        },
      );
      
      final result = await paytech.requestPayment(
        context: context,
        request: paymentRequest,
        onSuccess: (PaymentResult result) {
          _handlePaymentSuccess(result);
        },
        onError: (PaymentError error) {
          _handlePaymentError(error);
        },
        onCancel: () {
          _handlePaymentCancel();
        },
      );
      
    } catch (e) {
      _handlePaymentError(PaymentError(
        code: 'UNKNOWN_ERROR',
        message: e.toString(),
      ));
    } finally {
      setState(() {
        isLoading = false;
      });
    }
  }
  
  void _handlePaymentSuccess(PaymentResult result) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('Paiement réussi'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Référence: ${result.refCommand}'),
            Text('Montant: ${result.amount} ${result.currency}'),
            Text('Méthode: ${result.paymentMethod}'),
            Text('Token: ${result.token}'),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(context).pop(),
            child: Text('OK'),
          ),
        ],
      ),
    );
  }
  
  void _handlePaymentError(PaymentError error) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('Erreur de paiement'),
        content: Text('${error.code}: ${error.message}'),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(context).pop(),
            child: Text('OK'),
          ),
        ],
      ),
    );
  }
  
  void _handlePaymentCancel() {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text('Paiement annulé')),
    );
  }
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('PayTech Demo'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Card(
              margin: EdgeInsets.all(16),
              child: Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  children: [
                    Text(
                      'iPhone 13 Pro',
                      style: Theme.of(context).textTheme.headlineSmall,
                    ),
                    SizedBox(height: 8),
                    Text(
                      '650 000 FCFA',
                      style: Theme.of(context).textTheme.titleLarge?.copyWith(
                        color: Colors.green,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    SizedBox(height: 16),
                    ElevatedButton(
                      onPressed: isLoading ? null : makePayment,
                      child: isLoading
                          ? CircularProgressIndicator()
                          : Text('Payer maintenant'),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
```

### Paiement avec options avancées

```dart
class AdvancedPaymentScreen extends StatefulWidget {
  @override
  _AdvancedPaymentScreenState createState() => _AdvancedPaymentScreenState();
}

class _AdvancedPaymentScreenState extends State<AdvancedPaymentScreen> {
  final PayTech paytech = PayTechManager.instance;
  Currency selectedCurrency = Currency.XOF;
  PaymentMethod? preferredMethod;
  
  Future<void> makeAdvancedPayment() async {
    try {
      final paymentRequest = PaymentRequest(
        itemName: 'Abonnement Premium',
        itemPrice: _getAmountForCurrency(),
        currency: selectedCurrency,
        refCommand: 'SUB_${DateTime.now().millisecondsSinceEpoch}',
        commandName: 'Abonnement Premium - 1 mois',
        customField: {
          'subscription_type': 'premium',
          'duration': '1_month',
          'user_id': '12345',
        },
        // URLs personnalisées
        successUrl: 'https://monapp.com/payment/success',
        cancelUrl: 'https://monapp.com/payment/cancel',
        ipnUrl: 'https://monapp.com/webhooks/paytech',
      );
      
      final options = PaymentOptions(
        preferredMethod: preferredMethod,
        showMethodSelection: true,
        enableSaveCard: true,
        theme: PaymentTheme(
          primaryColor: Colors.blue,
          backgroundColor: Colors.white,
          textColor: Colors.black87,
        ),
        locale: 'fr', // ou 'en'
      );
      
      await paytech.requestPayment(
        context: context,
        request: paymentRequest,
        options: options,
        onSuccess: _handleSuccess,
        onError: _handleError,
        onCancel: _handleCancel,
        onMethodSelected: (PaymentMethod method) {
          print('Méthode sélectionnée: ${method.name}');
        },
      );
      
    } catch (e) {
      _handleError(PaymentError(
        code: 'REQUEST_ERROR',
        message: e.toString(),
      ));
    }
  }
  
  double _getAmountForCurrency() {
    switch (selectedCurrency) {
      case Currency.XOF:
        return 15000; // 15 000 FCFA
      case Currency.EUR:
        return 25.0; // 25 EUR
      case Currency.USD:
        return 30.0; // 30 USD
      default:
        return 15000;
    }
  }
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Paiement Avancé'),
      ),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Card(
              child: Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  children: [
                    Text(
                      'Abonnement Premium',
                      style: Theme.of(context).textTheme.headlineSmall,
                    ),
                    SizedBox(height: 16),
                    
                    // Sélection de devise
                    DropdownButtonFormField<Currency>(
                      value: selectedCurrency,
                      decoration: InputDecoration(
                        labelText: 'Devise',
                        border: OutlineInputBorder(),
                      ),
                      items: Currency.values.map((currency) {
                        return DropdownMenuItem(
                          value: currency,
                          child: Text(currency.toString().split('.').last),
                        );
                      }).toList(),
                      onChanged: (Currency? value) {
                        if (value != null) {
                          setState(() {
                            selectedCurrency = value;
                          });
                        }
                      },
                    ),
                    
                    SizedBox(height: 16),
                    
                    // Méthode de paiement préférée
                    DropdownButtonFormField<PaymentMethod?>(
                      value: preferredMethod,
                      decoration: InputDecoration(
                        labelText: 'Méthode préférée (optionnel)',
                        border: OutlineInputBorder(),
                      ),
                      items: [
                        DropdownMenuItem(
                          value: null,
                          child: Text('Laisser l\'utilisateur choisir'),
                        ),
                        ...PaymentMethod.values.map((method) {
                          return DropdownMenuItem(
                            value: method,
                            child: Text(method.displayName),
                          );
                        }),
                      ],
                      onChanged: (PaymentMethod? value) {
                        setState(() {
                          preferredMethod = value;
                        });
                      },
                    ),
                    
                    SizedBox(height: 24),
                    
                    Text(
                      'Montant: ${_getAmountForCurrency()} ${selectedCurrency.toString().split('.').last}',
                      style: Theme.of(context).textTheme.titleLarge?.copyWith(
                        color: Colors.green,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    
                    SizedBox(height: 16),
                    
                    ElevatedButton(
                      onPressed: makeAdvancedPayment,
                      child: Text('Souscrire'),
                      style: ElevatedButton.styleFrom(
                        padding: EdgeInsets.symmetric(vertical: 16),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
  
  void _handleSuccess(PaymentResult result) {
    // Traitement du succès
  }
  
  void _handleError(PaymentError error) {
    // Traitement de l'erreur
  }
  
  void _handleCancel() {
    // Traitement de l'annulation
  }
}
```

## Gestion des états et callbacks

### Utilisation avec Provider

```dart
import 'package:provider/provider.dart';

class PaymentProvider extends ChangeNotifier {
  PaymentState _state = PaymentState.idle;
  PaymentResult? _lastResult;
  PaymentError? _lastError;
  
  PaymentState get state => _state;
  PaymentResult? get lastResult => _lastResult;
  PaymentError? get lastError => _lastError;
  
  Future<void> processPayment(PaymentRequest request) async {
    _setState(PaymentState.loading);
    
    try {
      final paytech = PayTechManager.instance;
      
      await paytech.requestPayment(
        request: request,
        onSuccess: (result) {
          _lastResult = result;
          _setState(PaymentState.success);
        },
        onError: (error) {
          _lastError = error;
          _setState(PaymentState.error);
        },
        onCancel: () {
          _setState(PaymentState.cancelled);
        },
      );
      
    } catch (e) {
      _lastError = PaymentError(
        code: 'PROVIDER_ERROR',
        message: e.toString(),
      );
      _setState(PaymentState.error);
    }
  }
  
  void _setState(PaymentState newState) {
    _state = newState;
    notifyListeners();
  }
  
  void reset() {
    _state = PaymentState.idle;
    _lastResult = null;
    _lastError = null;
    notifyListeners();
  }
}

enum PaymentState {
  idle,
  loading,
  success,
  error,
  cancelled,
}

// Utilisation dans un widget
class PaymentWidget extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Consumer<PaymentProvider>(
      builder: (context, paymentProvider, child) {
        switch (paymentProvider.state) {
          case PaymentState.loading:
            return CircularProgressIndicator();
          case PaymentState.success:
            return SuccessWidget(result: paymentProvider.lastResult!);
          case PaymentState.error:
            return ErrorWidget(error: paymentProvider.lastError!);
          case PaymentState.cancelled:
            return CancelledWidget();
          default:
            return PaymentFormWidget();
        }
      },
    );
  }
}
```

### Gestion des callbacks avec Streams

```dart
class PaymentStreamManager {
  final StreamController<PaymentEvent> _controller = 
      StreamController<PaymentEvent>.broadcast();
  
  Stream<PaymentEvent> get paymentStream => _controller.stream;
  
  void dispose() {
    _controller.close();
  }
  
  Future<void> processPayment(PaymentRequest request) async {
    _controller.add(PaymentEvent.loading());
    
    try {
      final paytech = PayTechManager.instance;
      
      await paytech.requestPayment(
        request: request,
        onSuccess: (result) {
          _controller.add(PaymentEvent.success(result));
        },
        onError: (error) {
          _controller.add(PaymentEvent.error(error));
        },
        onCancel: () {
          _controller.add(PaymentEvent.cancelled());
        },
      );
      
    } catch (e) {
      _controller.add(PaymentEvent.error(
        PaymentError(code: 'STREAM_ERROR', message: e.toString())
      ));
    }
  }
}

abstract class PaymentEvent {
  factory PaymentEvent.loading() = PaymentLoadingEvent;
  factory PaymentEvent.success(PaymentResult result) = PaymentSuccessEvent;
  factory PaymentEvent.error(PaymentError error) = PaymentErrorEvent;
  factory PaymentEvent.cancelled() = PaymentCancelledEvent;
}

class PaymentLoadingEvent implements PaymentEvent {}
class PaymentSuccessEvent implements PaymentEvent {
  final PaymentResult result;
  PaymentSuccessEvent(this.result);
}
class PaymentErrorEvent implements PaymentEvent {
  final PaymentError error;
  PaymentErrorEvent(this.error);
}
class PaymentCancelledEvent implements PaymentEvent {}
```

## Personnalisation de l'interface

### Thème personnalisé

```dart
class CustomPaymentTheme {
  static PaymentTheme get theme {
    return PaymentTheme(
      primaryColor: Color(0xFF135571), // Couleur PayTech
      secondaryColor: Color(0xFF2c5aa0),
      backgroundColor: Colors.white,
      surfaceColor: Color(0xFFF8F9FA),
      textColor: Color(0xFF2c3e50),
      subtitleColor: Color(0xFF7f8c8d),
      errorColor: Color(0xFFe74c3c),
      successColor: Color(0xFF27ae60),
      
      // Styles de boutons
      buttonStyle: ButtonStyle(
        backgroundColor: MaterialStateProperty.all(Color(0xFF135571)),
        foregroundColor: MaterialStateProperty.all(Colors.white),
        padding: MaterialStateProperty.all(
          EdgeInsets.symmetric(horizontal: 24, vertical: 12)
        ),
        shape: MaterialStateProperty.all(
          RoundedRectangleBorder(borderRadius: BorderRadius.circular(8))
        ),
      ),
      
      // Styles de champs de saisie
      inputDecoration: InputDecoration(
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: BorderSide(color: Color(0xFFE0E0E0)),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: BorderSide(color: Color(0xFF135571), width: 2),
        ),
        contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      ),
      
      // Styles de cartes
      cardTheme: CardTheme(
        elevation: 2,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        margin: EdgeInsets.all(8),
      ),
    );
  }
}
```

### Widget personnalisé de méthodes de paiement

```dart
class CustomPaymentMethodSelector extends StatefulWidget {
  final List<PaymentMethod> availableMethods;
  final PaymentMethod? selectedMethod;
  final ValueChanged<PaymentMethod> onMethodSelected;
  
  const CustomPaymentMethodSelector({
    Key? key,
    required this.availableMethods,
    this.selectedMethod,
    required this.onMethodSelected,
  }) : super(key: key);
  
  @override
  _CustomPaymentMethodSelectorState createState() => 
      _CustomPaymentMethodSelectorState();
}

class _CustomPaymentMethodSelectorState 
    extends State<CustomPaymentMethodSelector> {
  
  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Choisissez votre méthode de paiement',
          style: Theme.of(context).textTheme.titleMedium,
        ),
        SizedBox(height: 16),
        ...widget.availableMethods.map((method) {
          return Card(
            margin: EdgeInsets.only(bottom: 8),
            child: ListTile(
              leading: _getMethodIcon(method),
              title: Text(method.displayName),
              subtitle: Text(method.description),
              trailing: Radio<PaymentMethod>(
                value: method,
                groupValue: widget.selectedMethod,
                onChanged: (PaymentMethod? value) {
                  if (value != null) {
                    widget.onMethodSelected(value);
                  }
                },
              ),
              onTap: () => widget.onMethodSelected(method),
            ),
          );
        }).toList(),
      ],
    );
  }
  
  Widget _getMethodIcon(PaymentMethod method) {
    switch (method) {
      case PaymentMethod.orangeMoney:
        return Image.asset('assets/icons/orange_money.png', width: 32);
      case PaymentMethod.tigoCash:
        return Image.asset('assets/icons/tigo_cash.png', width: 32);
      case PaymentMethod.emoney:
        return Image.asset('assets/icons/emoney.png', width: 32);
      case PaymentMethod.wave:
        return Image.asset('assets/icons/wave.png', width: 32);
      case PaymentMethod.card:
        return Icon(Icons.credit_card, color: Colors.blue);
      default:
        return Icon(Icons.payment, color: Colors.grey);
    }
  }
}
```

## Tests et debugging

### Tests unitaires

```dart
import 'package:flutter_test/flutter_test.dart';
import 'package:mockito/mockito.dart';
import 'package:paytech_flutter/paytech_flutter.dart';

class MockPayTech extends Mock implements PayTech {}

void main() {
  group('PayTech Flutter Tests', () {
    late MockPayTech mockPayTech;
    
    setUp(() {
      mockPayTech = MockPayTech();
    });
    
    test('should create payment request correctly', () {
      final request = PaymentRequest(
        itemName: 'Test Product',
        itemPrice: 1000,
        currency: Currency.XOF,
        refCommand: 'TEST_001',
        commandName: 'Test Command',
      );
      
      expect(request.itemName, equals('Test Product'));
      expect(request.itemPrice, equals(1000));
      expect(request.currency, equals(Currency.XOF));
      expect(request.refCommand, equals('TEST_001'));
    });
    
    test('should validate payment request', () {
      final validRequest = PaymentRequest(
        itemName: 'Valid Product',
        itemPrice: 1000,
        currency: Currency.XOF,
        refCommand: 'VALID_001',
        commandName: 'Valid Command',
      );
      
      expect(validRequest.isValid, isTrue);
      
      final invalidRequest = PaymentRequest(
        itemName: '',
        itemPrice: -100,
        currency: Currency.XOF,
        refCommand: '',
        commandName: '',
      );
      
      expect(invalidRequest.isValid, isFalse);
    });
    
    test('should handle payment success', () async {
      final request = PaymentRequest(
        itemName: 'Test Product',
        itemPrice: 1000,
        currency: Currency.XOF,
        refCommand: 'TEST_001',
        commandName: 'Test Command',
      );
      
      final expectedResult = PaymentResult(
        success: true,
        token: 'test_token',
        refCommand: 'TEST_001',
        amount: 1000,
        currency: Currency.XOF,
        paymentMethod: PaymentMethod.orangeMoney,
      );
      
      when(mockPayTech.requestPayment(
        request: request,
        onSuccess: anyNamed('onSuccess'),
        onError: anyNamed('onError'),
        onCancel: anyNamed('onCancel'),
      )).thenAnswer((_) async {
        // Simuler un succès
        return expectedResult;
      });
      
      final result = await mockPayTech.requestPayment(
        request: request,
        onSuccess: (result) {},
        onError: (error) {},
        onCancel: () {},
      );
      
      expect(result.success, isTrue);
      expect(result.token, equals('test_token'));
    });
  });
}
```

### Tests d'intégration

```dart
import 'package:flutter/services.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:paytech_flutter_example/main.dart' as app;

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();
  
  group('PayTech Integration Tests', () {
    testWidgets('should complete payment flow', (WidgetTester tester) async {
      app.main();
      await tester.pumpAndSettle();
      
      // Trouver le bouton de paiement
      final payButton = find.text('Payer maintenant');
      expect(payButton, findsOneWidget);
      
      // Appuyer sur le bouton
      await tester.tap(payButton);
      await tester.pumpAndSettle();
      
      // Vérifier que la page de paiement s'ouvre
      // (Ceci dépend de votre implémentation UI)
      
      // Simuler la sélection d'une méthode de paiement
      final orangeMoneyOption = find.text('Orange Money');
      if (orangeMoneyOption.evaluate().isNotEmpty) {
        await tester.tap(orangeMoneyOption);
        await tester.pumpAndSettle();
      }
      
      // Continuer le flux de test...
    });
    
    testWidgets('should handle payment cancellation', (WidgetTester tester) async {
      app.main();
      await tester.pumpAndSettle();
      
      // Déclencher un paiement
      final payButton = find.text('Payer maintenant');
      await tester.tap(payButton);
      await tester.pumpAndSettle();
      
      // Simuler l'annulation
      final cancelButton = find.text('Annuler');
      if (cancelButton.evaluate().isNotEmpty) {
        await tester.tap(cancelButton);
        await tester.pumpAndSettle();
      }
      
      // Vérifier que l'annulation est gérée correctement
      expect(find.text('Paiement annulé'), findsOneWidget);
    });
  });
}
```

## Déploiement et optimisation

### Configuration de production

```dart
class ProductionConfig {
  static const bool enableLogging = false;
  static const bool enableAnalytics = true;
  static const Duration networkTimeout = Duration(seconds: 30);
  
  static PayTech createPayTechInstance() {
    return PayTech(
      apiKey: const String.fromEnvironment('PAYTECH_API_KEY'),
      apiSecret: const String.fromEnvironment('PAYTECH_API_SECRET'),
      environment: Environment.production,
      config: PayTechConfiguration(
        timeout: networkTimeout,
        retryAttempts: 3,
        enableLogging: enableLogging,
        enableAnalytics: enableAnalytics,
      ),
    );
  }
}
```

### Optimisation des performances

```dart
class PayTechOptimizer {
  static void optimizeForProduction() {
    // Préchargement des ressources
    _preloadAssets();
    
    // Configuration du cache
    _configureCaching();
    
    // Optimisation réseau
    _optimizeNetworking();
  }
  
  static void _preloadAssets() {
    // Précharger les icônes des méthodes de paiement
    final assetPaths = [
      'assets/icons/orange_money.png',
      'assets/icons/tigo_cash.png',
      'assets/icons/emoney.png',
      'assets/icons/wave.png',
    ];
    
    for (final path in assetPaths) {
      precacheImage(AssetImage(path), navigatorKey.currentContext!);
    }
  }
  
  static void _configureCaching() {
    // Configuration du cache pour les réponses API
    PayTechManager.instance.setCachePolicy(
      CachePolicy(
        maxAge: Duration(minutes: 5),
        maxEntries: 100,
      ),
    );
  }
  
  static void _optimizeNetworking() {
    // Configuration des timeouts et retry
    PayTechManager.instance.setNetworkConfig(
      NetworkConfig(
        connectTimeout: Duration(seconds: 10),
        receiveTimeout: Duration(seconds: 30),
        retryAttempts: 3,
        retryDelay: Duration(seconds: 2),
      ),
    );
  }
}
```

---

> **Note** : Cette documentation couvre les fonctionnalités principales du SDK Flutter PayTech. Consultez la documentation officielle du package pour les dernières mises à jour et fonctionnalités avancées.


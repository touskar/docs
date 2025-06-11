# React Native - Intégration PayTech

Cette section détaille l'intégration de PayTech dans une application React Native, incluant les composants, hooks, navigation, et la gestion des paiements via WebView.

## Vue d'ensemble

React Native offre une approche cross-platform pour intégrer PayTech avec :

- **WebView** pour l'intégration web (react-native-webview)
- **Linking** pour les URL schemes de retour
- **AsyncStorage** pour la persistance locale
- **Fetch/Axios** pour les appels API
- **React Navigation** pour la navigation

## Prérequis

- **Node.js** : Version 16 ou supérieure
- **React Native** : Version 0.70 ou supérieure
- **React Native CLI** ou **Expo CLI**
- **Android Studio** et **Xcode** pour le développement natif

## Installation et configuration

### Installation des dépendances

```bash
# Navigation
npm install @react-navigation/native @react-navigation/stack @react-navigation/bottom-tabs
npm install react-native-screens react-native-safe-area-context

# WebView
npm install react-native-webview

# Storage
npm install @react-native-async-storage/async-storage

# Networking
npm install axios

# UI Components
npm install react-native-vector-icons
npm install react-native-modal
npm install react-native-progress

# Utils
npm install react-native-device-info
npm install react-native-orientation-locker

# iOS
cd ios && pod install
```

### Configuration Android

#### android/app/src/main/AndroidManifest.xml

```xml
<!-- android/app/src/main/AndroidManifest.xml -->
<manifest xmlns:android="http://schemas.android.com/apk/res/android">
    
    <!-- Permissions -->
    <uses-permission android:name="android.permission.INTERNET" />
    <uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
    
    <application
        android:name=".MainApplication"
        android:allowBackup="false"
        android:icon="@mipmap/ic_launcher"
        android:label="@string/app_name"
        android:theme="@style/AppTheme"
        android:usesCleartextTraffic="true">
        
        <!-- Activité principale -->
        <activity
            android:name=".MainActivity"
            android:exported="true"
            android:launchMode="singleTop"
            android:theme="@style/AppTheme"
            android:windowSoftInputMode="adjustResize">
            
            <intent-filter>
                <action android:name="android.intent.action.MAIN" />
                <category android:name="android.intent.category.LAUNCHER" />
            </intent-filter>
            
            <!-- Intent filter pour les retours PayTech -->
            <intent-filter>
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="paytech" android:host="payment" />
            </intent-filter>
        </activity>
    </application>
</manifest>
```

### Configuration iOS

#### ios/PayTechApp/Info.plist

```xml
<!-- ios/PayTechApp/Info.plist -->
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <!-- Configuration réseau -->
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
                <key>NSIncludesSubdomains</key>
                <true/>
            </dict>
        </dict>
    </dict>
    
    <!-- URL Schemes pour les retours PayTech -->
    <key>CFBundleURLTypes</key>
    <array>
        <dict>
            <key>CFBundleURLName</key>
            <string>com.votre.app.paytech</string>
            <key>CFBundleURLSchemes</key>
            <array>
                <string>paytech</string>
            </array>
        </dict>
    </array>
</dict>
</plist>
```

## Configuration PayTech

### src/config/paytech.js

```javascript
// src/config/paytech.js
import { Platform } from 'react-native';
import DeviceInfo from 'react-native-device-info';

export const PAYTECH_CONFIG = {
  // URLs de votre backend
  API_BASE_URL: __DEV__ 
    ? 'http://localhost:3001/api' 
    : 'https://votre-backend.com/api',
  
  // Configuration PayTech
  PAYMENT_METHODS: [
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
  
  // URLs de retour mobile
  MOBILE_URLS: {
    success: 'paytech://payment/success',
    cancel: 'paytech://payment/cancel',
    error: 'paytech://payment/error'
  },
  
  // URLs de retour web (fallback)
  WEB_URLS: {
    success: 'https://paytech.sn/mobile/success',
    cancel: 'https://paytech.sn/mobile/cancel'
  },
  
  // Configuration WebView
  WEBVIEW_CONFIG: {
    timeout: 30000,
    userAgent: `PayTechRN/${DeviceInfo.getVersion()} (${Platform.OS} ${DeviceInfo.getSystemVersion()})`,
    javaScriptEnabled: true,
    domStorageEnabled: true,
    startInLoadingState: true,
    scalesPageToFit: Platform.OS === 'android',
    allowsInlineMediaPlayback: true,
    mediaPlaybackRequiresUserAction: false
  },
  
  // Configuration mobile
  MOBILE_CONFIG: {
    autoSubmitEnabled: true,
    prefillEnabled: true,
    orientationLock: 'portrait'
  },
  
  // Configuration de l'environnement
  ENVIRONMENT: __DEV__ ? 'development' : 'production',
  DEBUG_MODE: __DEV__
};
```

## Services

### src/services/PayTechService.js

```javascript
// src/services/PayTechService.js
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { PAYTECH_CONFIG } from '../config/paytech';

class PayTechService {
  constructor() {
    this.api = axios.create({
      baseURL: PAYTECH_CONFIG.API_BASE_URL,
      timeout: PAYTECH_CONFIG.WEBVIEW_CONFIG.timeout,
      headers: {
        'Content-Type': 'application/json',
        'User-Agent': PAYTECH_CONFIG.WEBVIEW_CONFIG.userAgent
      }
    });

    // Intercepteur pour les erreurs
    this.api.interceptors.response.use(
      (response) => response,
      (error) => {
        console.error('PayTech API Error:', error);
        return Promise.reject(this.handleError(error));
      }
    );
  }

  /**
   * Créer une demande de paiement
   */
  async requestPayment(paymentData) {
    try {
      const response = await this.api.post('/payment/request', paymentData);
      
      if (response.data.success) {
        // Sauvegarder la transaction localement
        await this.saveTransaction(response.data.transaction);
        
        return {
          success: true,
          data: response.data
        };
      } else {
        throw new Error(response.data.error || 'Erreur de paiement');
      }
    } catch (error) {
      return {
        success: false,
        error: error.message || 'Erreur de paiement'
      };
    }
  }

  /**
   * Vérifier le statut d'une transaction
   */
  async checkTransactionStatus(refCommand) {
    try {
      const response = await this.api.get(`/payment/status/${refCommand}`);
      
      // Mettre à jour la transaction locale
      await this.updateTransactionStatus(refCommand, response.data);
      
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      return {
        success: false,
        error: error.message || 'Erreur de vérification'
      };
    }
  }

  /**
   * Obtenir l'historique des transactions
   */
  async getUserTransactions(userId) {
    try {
      const response = await this.api.get(`/payment/history/${userId}`);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      return {
        success: false,
        error: error.message || 'Erreur de récupération'
      };
    }
  }

  /**
   * Sauvegarder une transaction localement
   */
  async saveTransaction(transaction) {
    try {
      const transactions = await this.getLocalTransactions();
      transactions.unshift(transaction);
      
      // Garder seulement les 50 dernières transactions
      const limitedTransactions = transactions.slice(0, 50);
      
      await AsyncStorage.setItem(
        'paytech_transactions',
        JSON.stringify(limitedTransactions)
      );
    } catch (error) {
      console.error('Error saving transaction:', error);
    }
  }

  /**
   * Récupérer les transactions locales
   */
  async getLocalTransactions() {
    try {
      const transactions = await AsyncStorage.getItem('paytech_transactions');
      return transactions ? JSON.parse(transactions) : [];
    } catch (error) {
      console.error('Error getting local transactions:', error);
      return [];
    }
  }

  /**
   * Mettre à jour le statut d'une transaction locale
   */
  async updateTransactionStatus(refCommand, statusData) {
    try {
      const transactions = await this.getLocalTransactions();
      const index = transactions.findIndex(t => t.refCommand === refCommand);
      
      if (index !== -1) {
        transactions[index] = {
          ...transactions[index],
          status: statusData.status,
          completedAt: statusData.completedAt,
          updatedAt: new Date().toISOString()
        };
        
        await AsyncStorage.setItem(
          'paytech_transactions',
          JSON.stringify(transactions)
        );
      }
    } catch (error) {
      console.error('Error updating transaction status:', error);
    }
  }

  /**
   * Générer une référence de commande unique
   */
  generateRefCommand() {
    const timestamp = Date.now();
    const random = Math.random().toString(36).substr(2, 9);
    return `CMD_${timestamp}_${random}`;
  }

  /**
   * Gestion des erreurs
   */
  handleError(error) {
    if (error.response) {
      // Erreur de réponse du serveur
      return new Error(
        error.response.data?.message || 
        `Erreur HTTP: ${error.response.status}`
      );
    } else if (error.request) {
      // Erreur de réseau
      return new Error('Erreur de connexion réseau');
    } else {
      // Autre erreur
      return new Error(error.message || 'Erreur inconnue');
    }
  }
}

export default new PayTechService();
```

## Hooks personnalisés

### src/hooks/usePayment.js

```javascript
// src/hooks/usePayment.js
import { useState, useCallback } from 'react';
import { Platform } from 'react-native';
import DeviceInfo from 'react-native-device-info';
import PayTechService from '../services/PayTechService';
import { PAYTECH_CONFIG } from '../config/paytech';

export const usePayment = () => {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);
  const [currentTransaction, setCurrentTransaction] = useState(null);

  /**
   * Initier un paiement
   */
  const initiatePayment = useCallback(async (paymentData) => {
    try {
      setLoading(true);
      setError(null);

      // Génération de la référence de commande si non fournie
      if (!paymentData.refCommand) {
        paymentData.refCommand = PayTechService.generateRefCommand();
      }

      // Ajout des informations de l'appareil
      const deviceInfo = await getDeviceInfo();
      
      const requestData = {
        ...paymentData,
        mobileIntegration: true,
        customField: {
          ...paymentData.customField,
          source: 'react_native',
          platform: Platform.OS,
          device: deviceInfo,
          timestamp: new Date().toISOString()
        }
      };

      // Appel au service PayTech
      const result = await PayTechService.requestPayment(requestData);

      if (result.success) {
        setCurrentTransaction(result.data.transaction);
        setSuccess('Redirection vers PayTech...');
        
        return {
          success: true,
          redirectUrl: result.data.data.redirect_url,
          transaction: result.data.transaction
        };
      } else {
        throw new Error(result.error);
      }
    } catch (err) {
      setError(err.message);
      return {
        success: false,
        error: err.message
      };
    } finally {
      setLoading(false);
    }
  }, []);

  /**
   * Vérifier le statut d'un paiement
   */
  const checkPaymentStatus = useCallback(async (refCommand) => {
    try {
      setLoading(true);
      setError(null);

      const result = await PayTechService.checkTransactionStatus(refCommand);

      if (result.success) {
        setSuccess('Statut récupéré');
        return result.data;
      } else {
        throw new Error(result.error);
      }
    } catch (err) {
      setError(err.message);
      return null;
    } finally {
      setLoading(false);
    }
  }, []);

  /**
   * Réinitialiser l'état
   */
  const resetPayment = useCallback(() => {
    setLoading(false);
    setError(null);
    setSuccess(null);
    setCurrentTransaction(null);
  }, []);

  /**
   * Effacer les messages
   */
  const clearMessages = useCallback(() => {
    setError(null);
    setSuccess(null);
  }, []);

  return {
    loading,
    error,
    success,
    currentTransaction,
    initiatePayment,
    checkPaymentStatus,
    resetPayment,
    clearMessages
  };
};

/**
 * Obtenir les informations de l'appareil
 */
const getDeviceInfo = async () => {
  try {
    const [
      deviceId,
      brand,
      model,
      systemVersion,
      appVersion,
      buildNumber
    ] = await Promise.all([
      DeviceInfo.getUniqueId(),
      DeviceInfo.getBrand(),
      DeviceInfo.getModel(),
      DeviceInfo.getSystemVersion(),
      DeviceInfo.getVersion(),
      DeviceInfo.getBuildNumber()
    ]);

    return {
      deviceId,
      brand,
      model,
      systemVersion,
      appVersion,
      buildNumber,
      platform: Platform.OS
    };
  } catch (error) {
    console.error('Error getting device info:', error);
    return {
      platform: Platform.OS
    };
  }
};
```

### src/hooks/useTransactionStatus.js

```javascript
// src/hooks/useTransactionStatus.js
import { useState, useEffect, useCallback, useRef } from 'react';
import PayTechService from '../services/PayTechService';

export const useTransactionStatus = (refCommand, autoRefresh = false, refreshInterval = 5000) => {
  const [status, setStatus] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const intervalRef = useRef(null);

  /**
   * Vérifier le statut
   */
  const checkStatus = useCallback(async () => {
    if (!refCommand) return;

    setLoading(true);
    setError(null);

    try {
      const result = await PayTechService.checkTransactionStatus(refCommand);

      if (result.success) {
        setStatus(result.data);
        
        // Arrêter le refresh automatique si le statut est final
        if (autoRefresh && isStatusFinal(result.data.status)) {
          stopAutoRefresh();
        }
      } else {
        throw new Error(result.error);
      }
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  }, [refCommand, autoRefresh]);

  /**
   * Démarrer le refresh automatique
   */
  const startAutoRefresh = useCallback(() => {
    if (intervalRef.current || !autoRefresh) return;

    intervalRef.current = setInterval(() => {
      if (status && !isStatusFinal(status.status)) {
        checkStatus();
      }
    }, refreshInterval);
  }, [autoRefresh, refreshInterval, status, checkStatus]);

  /**
   * Arrêter le refresh automatique
   */
  const stopAutoRefresh = useCallback(() => {
    if (intervalRef.current) {
      clearInterval(intervalRef.current);
      intervalRef.current = null;
    }
  }, []);

  /**
   * Vérifier si le statut est final
   */
  const isStatusFinal = (status) => {
    return ['COMPLETED', 'FAILED', 'CANCELLED'].includes(status);
  };

  // Effect pour le chargement initial
  useEffect(() => {
    checkStatus();
  }, [checkStatus]);

  // Effect pour le refresh automatique
  useEffect(() => {
    if (autoRefresh && status && !isStatusFinal(status.status)) {
      startAutoRefresh();
    }

    return () => {
      stopAutoRefresh();
    };
  }, [autoRefresh, status, startAutoRefresh, stopAutoRefresh]);

  return {
    status,
    loading,
    error,
    checkStatus,
    startAutoRefresh,
    stopAutoRefresh,
    isCompleted: status?.status === 'COMPLETED',
    isPending: status?.status === 'PENDING',
    isFailed: status?.status === 'FAILED',
    isCancelled: status?.status === 'CANCELLED'
  };
};
```

## Composants

### src/components/PaymentForm.js

```javascript
// src/components/PaymentForm.js
import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  ScrollView,
  Alert,
  Switch,
  StyleSheet,
  ActivityIndicator
} from 'react-native';
import { Picker } from '@react-native-picker/picker';
import Icon from 'react-native-vector-icons/MaterialIcons';
import { usePayment } from '../hooks/usePayment';
import { PAYTECH_CONFIG } from '../config/paytech';

const PaymentForm = ({ onSuccess, onError }) => {
  const { loading, error, initiatePayment, clearMessages } = usePayment();
  
  const [formData, setFormData] = useState({
    itemName: '',
    itemPrice: '',
    paymentMethod: '',
    userPhone: '',
    userFirstName: '',
    userLastName: '',
    autoSubmit: PAYTECH_CONFIG.MOBILE_CONFIG.autoSubmitEnabled,
    mobileIntegration: true
  });

  const handleInputChange = (field, value) => {
    setFormData(prev => ({
      ...prev,
      [field]: value
    }));
    
    // Effacer les messages d'erreur lors de la saisie
    if (error) {
      clearMessages();
    }
  };

  const handleSubmit = async () => {
    // Validation
    if (!formData.itemName.trim() || !formData.itemPrice.trim()) {
      const errorMsg = 'Veuillez remplir tous les champs obligatoires';
      onError?.(errorMsg);
      Alert.alert('Erreur', errorMsg);
      return;
    }

    const itemPrice = parseInt(formData.itemPrice);
    if (isNaN(itemPrice) || itemPrice < 100) {
      const errorMsg = 'Le montant minimum est de 100 FCFA';
      onError?.(errorMsg);
      Alert.alert('Erreur', errorMsg);
      return;
    }

    // Préparation des données de paiement
    const paymentData = {
      itemName: formData.itemName.trim(),
      itemPrice: itemPrice,
      currency: 'XOF',
      paymentMethod: formData.paymentMethod || null,
      mobileIntegration: formData.mobileIntegration,
      customField: {
        source: 'react_native_app',
        timestamp: new Date().toISOString()
      }
    };

    // Ajout des informations utilisateur pour préfillage
    if (formData.userPhone.trim() || formData.userFirstName.trim() || formData.userLastName.trim()) {
      paymentData.user = {
        phoneNumber: formData.userPhone.trim() || null,
        firstName: formData.userFirstName.trim() || null,
        lastName: formData.userLastName.trim() || null,
        autoSubmit: formData.autoSubmit
      };
    }

    // Initiation du paiement
    const result = await initiatePayment(paymentData);
    
    if (result.success) {
      onSuccess?.(result);
    } else {
      onError?.(result.error);
      Alert.alert('Erreur de paiement', result.error);
    }
  };

  return (
    <ScrollView style={styles.container} showsVerticalScrollIndicator={false}>
      <View style={styles.card}>
        <Text style={styles.title}>Paiement PayTech</Text>
        
        {/* Messages d'erreur */}
        {error && (
          <View style={styles.errorContainer}>
            <Icon name="error" size={20} color="#dc3545" />
            <Text style={styles.errorText}>{error}</Text>
          </View>
        )}

        {/* Informations du produit */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Informations du produit</Text>
          
          <View style={styles.inputContainer}>
            <Text style={styles.label}>Produit/Service *</Text>
            <TextInput
              style={styles.input}
              value={formData.itemName}
              onChangeText={(value) => handleInputChange('itemName', value)}
              placeholder="iPhone 13 Pro"
              placeholderTextColor="#999"
            />
          </View>

          <View style={styles.inputContainer}>
            <Text style={styles.label}>Montant (FCFA) *</Text>
            <TextInput
              style={styles.input}
              value={formData.itemPrice}
              onChangeText={(value) => handleInputChange('itemPrice', value)}
              placeholder="650000"
              placeholderTextColor="#999"
              keyboardType="numeric"
            />
          </View>

          <View style={styles.inputContainer}>
            <Text style={styles.label}>Méthode de paiement (optionnel)</Text>
            <View style={styles.pickerContainer}>
              <Picker
                selectedValue={formData.paymentMethod}
                onValueChange={(value) => handleInputChange('paymentMethod', value)}
                style={styles.picker}
              >
                <Picker.Item label="Choisir sur PayTech" value="" />
                {PAYTECH_CONFIG.PAYMENT_METHODS.map(method => (
                  <Picker.Item key={method} label={method} value={method} />
                ))}
              </Picker>
            </View>
          </View>
        </View>

        {/* Informations de préfillage */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Informations de préfillage (optionnel)</Text>
          
          <View style={styles.row}>
            <View style={[styles.inputContainer, styles.halfWidth]}>
              <Text style={styles.label}>Prénom</Text>
              <TextInput
                style={styles.input}
                value={formData.userFirstName}
                onChangeText={(value) => handleInputChange('userFirstName', value)}
                placeholder="John"
                placeholderTextColor="#999"
              />
            </View>
            
            <View style={[styles.inputContainer, styles.halfWidth]}>
              <Text style={styles.label}>Nom</Text>
              <TextInput
                style={styles.input}
                value={formData.userLastName}
                onChangeText={(value) => handleInputChange('userLastName', value)}
                placeholder="Doe"
                placeholderTextColor="#999"
              />
            </View>
          </View>

          <View style={styles.inputContainer}>
            <Text style={styles.label}>Téléphone</Text>
            <TextInput
              style={styles.input}
              value={formData.userPhone}
              onChangeText={(value) => handleInputChange('userPhone', value)}
              placeholder="+221771234567"
              placeholderTextColor="#999"
              keyboardType="phone-pad"
            />
          </View>

          <View style={styles.switchContainer}>
            <Text style={styles.switchLabel}>Soumission automatique après préfillage</Text>
            <Switch
              value={formData.autoSubmit}
              onValueChange={(value) => handleInputChange('autoSubmit', value)}
              trackColor={{ false: '#767577', true: '#007AFF' }}
              thumbColor={formData.autoSubmit ? '#fff' : '#f4f3f4'}
            />
          </View>

          <View style={styles.switchContainer}>
            <Text style={styles.switchLabel}>Intégration mobile</Text>
            <Switch
              value={formData.mobileIntegration}
              onValueChange={(value) => handleInputChange('mobileIntegration', value)}
              trackColor={{ false: '#767577', true: '#007AFF' }}
              thumbColor={formData.mobileIntegration ? '#fff' : '#f4f3f4'}
            />
          </View>
        </View>

        {/* Bouton de paiement */}
        <TouchableOpacity
          style={[styles.payButton, loading && styles.payButtonDisabled]}
          onPress={handleSubmit}
          disabled={loading}
        >
          {loading ? (
            <View style={styles.loadingContainer}>
              <ActivityIndicator color="#fff" size="small" />
              <Text style={styles.payButtonText}>Traitement...</Text>
            </View>
          ) : (
            <Text style={styles.payButtonText}>Payer avec PayTech</Text>
          )}
        </TouchableOpacity>

        {/* Informations de sécurité */}
        <View style={styles.securityContainer}>
          <Text style={styles.securityText}>🔒 Paiement 100% sécurisé</Text>
          <Text style={styles.securityText}>Propulsé par PayTech Sénégal</Text>
        </View>
      </View>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5'
  },
  card: {
    backgroundColor: '#fff',
    margin: 16,
    borderRadius: 12,
    padding: 20,
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 2
    },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    textAlign: 'center',
    marginBottom: 20,
    color: '#333'
  },
  errorContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f8d7da',
    borderColor: '#f5c6cb',
    borderWidth: 1,
    borderRadius: 8,
    padding: 12,
    marginBottom: 16
  },
  errorText: {
    color: '#721c24',
    marginLeft: 8,
    flex: 1
  },
  section: {
    marginBottom: 24
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: '600',
    marginBottom: 16,
    color: '#333'
  },
  inputContainer: {
    marginBottom: 16
  },
  label: {
    fontSize: 14,
    fontWeight: '500',
    marginBottom: 8,
    color: '#555'
  },
  input: {
    borderWidth: 1,
    borderColor: '#ddd',
    borderRadius: 8,
    padding: 12,
    fontSize: 16,
    backgroundColor: '#fff'
  },
  pickerContainer: {
    borderWidth: 1,
    borderColor: '#ddd',
    borderRadius: 8,
    backgroundColor: '#fff'
  },
  picker: {
    height: 50
  },
  row: {
    flexDirection: 'row',
    justifyContent: 'space-between'
  },
  halfWidth: {
    width: '48%'
  },
  switchContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12
  },
  switchLabel: {
    fontSize: 14,
    color: '#555',
    flex: 1,
    marginRight: 12
  },
  payButton: {
    backgroundColor: '#007AFF',
    borderRadius: 12,
    padding: 16,
    alignItems: 'center',
    marginTop: 8
  },
  payButtonDisabled: {
    backgroundColor: '#ccc'
  },
  loadingContainer: {
    flexDirection: 'row',
    alignItems: 'center'
  },
  payButtonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
    marginLeft: 8
  },
  securityContainer: {
    alignItems: 'center',
    marginTop: 20
  },
  securityText: {
    fontSize: 12,
    color: '#666',
    textAlign: 'center'
  }
});

export default PaymentForm;
```

### src/components/PayTechWebView.js

```javascript
// src/components/PayTechWebView.js
import React, { useState, useEffect, useRef } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  Alert,
  BackHandler,
  StyleSheet,
  SafeAreaView,
  StatusBar
} from 'react-native';
import { WebView } from 'react-native-webview';
import Icon from 'react-native-vector-icons/MaterialIcons';
import Orientation from 'react-native-orientation-locker';
import { PAYTECH_CONFIG } from '../config/paytech';

const PayTechWebView = ({ 
  redirectUrl, 
  refCommand, 
  onPaymentComplete, 
  onPaymentCancel, 
  onPaymentError,
  onClose 
}) => {
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [canGoBack, setCanGoBack] = useState(false);
  const [progress, setProgress] = useState(0);
  const webViewRef = useRef(null);

  useEffect(() => {
    // Verrouiller l'orientation si configuré
    if (PAYTECH_CONFIG.MOBILE_CONFIG.orientationLock === 'portrait') {
      Orientation.lockToPortrait();
    }

    // Gérer le bouton retour Android
    const backHandler = BackHandler.addEventListener('hardwareBackPress', handleBackPress);

    return () => {
      backHandler.remove();
      Orientation.unlockAllOrientations();
    };
  }, []);

  const handleBackPress = () => {
    if (canGoBack && webViewRef.current) {
      webViewRef.current.goBack();
      return true;
    } else {
      handleCancel();
      return true;
    }
  };

  const handleNavigationStateChange = (navState) => {
    const { url, canGoBack: webViewCanGoBack } = navState;
    setCanGoBack(webViewCanGoBack);

    // Vérifier les URLs de retour mobile
    if (url.startsWith(PAYTECH_CONFIG.MOBILE_URLS.success)) {
      handleSuccess(url);
      return;
    }

    if (url.startsWith(PAYTECH_CONFIG.MOBILE_URLS.cancel)) {
      handleCancel(url);
      return;
    }

    if (url.startsWith(PAYTECH_CONFIG.MOBILE_URLS.error)) {
      handleError(url);
      return;
    }

    // Vérifier les URLs de retour web (fallback)
    if (url.includes('paytech.sn/mobile/success')) {
      handleSuccess(url);
      return;
    }

    if (url.includes('paytech.sn/mobile/cancel')) {
      handleCancel(url);
      return;
    }
  };

  const handleSuccess = (url) => {
    onPaymentComplete?.(url, refCommand);
  };

  const handleCancel = (url = null) => {
    Alert.alert(
      'Annuler le paiement',
      'Êtes-vous sûr de vouloir annuler ce paiement ?',
      [
        { text: 'Continuer', style: 'cancel' },
        { 
          text: 'Annuler', 
          style: 'destructive',
          onPress: () => onPaymentCancel?.(url, refCommand)
        }
      ]
    );
  };

  const handleError = (url) => {
    onPaymentError?.(url, refCommand);
  };

  const handleLoadStart = () => {
    setLoading(true);
    setError(null);
  };

  const handleLoadEnd = () => {
    setLoading(false);
  };

  const handleLoadError = (syntheticEvent) => {
    const { nativeEvent } = syntheticEvent;
    setLoading(false);
    setError(`Erreur de chargement: ${nativeEvent.description}`);
  };

  const handleLoadProgress = ({ nativeEvent }) => {
    setProgress(nativeEvent.progress);
  };

  const handleRefresh = () => {
    if (webViewRef.current) {
      webViewRef.current.reload();
    }
  };

  const renderError = () => (
    <View style={styles.errorContainer}>
      <Icon name="error-outline" size={64} color="#dc3545" />
      <Text style={styles.errorTitle}>Erreur de chargement</Text>
      <Text style={styles.errorMessage}>{error}</Text>
      <TouchableOpacity style={styles.retryButton} onPress={handleRefresh}>
        <Text style={styles.retryButtonText}>Réessayer</Text>
      </TouchableOpacity>
    </View>
  );

  const renderHeader = () => (
    <View style={styles.header}>
      <TouchableOpacity style={styles.headerButton} onPress={handleCancel}>
        <Icon name="close" size={24} color="#fff" />
      </TouchableOpacity>
      
      <Text style={styles.headerTitle}>Paiement PayTech</Text>
      
      <TouchableOpacity style={styles.headerButton} onPress={handleRefresh}>
        <Icon name="refresh" size={24} color="#fff" />
      </TouchableOpacity>
    </View>
  );

  const renderProgressBar = () => (
    <View style={styles.progressContainer}>
      <View style={[styles.progressBar, { width: `${progress * 100}%` }]} />
    </View>
  );

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="light-content" backgroundColor="#007AFF" />
      
      {renderHeader()}
      {loading && renderProgressBar()}
      
      {error ? (
        renderError()
      ) : (
        <WebView
          ref={webViewRef}
          source={{ uri: redirectUrl }}
          style={styles.webView}
          onNavigationStateChange={handleNavigationStateChange}
          onLoadStart={handleLoadStart}
          onLoadEnd={handleLoadEnd}
          onError={handleLoadError}
          onLoadProgress={handleLoadProgress}
          startInLoadingState={PAYTECH_CONFIG.WEBVIEW_CONFIG.startInLoadingState}
          javaScriptEnabled={PAYTECH_CONFIG.WEBVIEW_CONFIG.javaScriptEnabled}
          domStorageEnabled={PAYTECH_CONFIG.WEBVIEW_CONFIG.domStorageEnabled}
          scalesPageToFit={PAYTECH_CONFIG.WEBVIEW_CONFIG.scalesPageToFit}
          allowsInlineMediaPlayback={PAYTECH_CONFIG.WEBVIEW_CONFIG.allowsInlineMediaPlayback}
          mediaPlaybackRequiresUserAction={PAYTECH_CONFIG.WEBVIEW_CONFIG.mediaPlaybackRequiresUserAction}
          userAgent={PAYTECH_CONFIG.WEBVIEW_CONFIG.userAgent}
          onShouldStartLoadWithRequest={(request) => {
            // Permettre seulement les URLs PayTech et les URLs de retour
            const { url } = request;
            return (
              url.includes('paytech.sn') ||
              url.startsWith(PAYTECH_CONFIG.MOBILE_URLS.success) ||
              url.startsWith(PAYTECH_CONFIG.MOBILE_URLS.cancel) ||
              url.startsWith(PAYTECH_CONFIG.MOBILE_URLS.error)
            );
          }}
        />
      )}
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#007AFF'
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    backgroundColor: '#007AFF',
    paddingHorizontal: 16,
    paddingVertical: 12,
    elevation: 4,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.2,
    shadowRadius: 4
  },
  headerButton: {
    padding: 8,
    borderRadius: 20,
    backgroundColor: 'rgba(255, 255, 255, 0.2)'
  },
  headerTitle: {
    color: '#fff',
    fontSize: 18,
    fontWeight: '600',
    flex: 1,
    textAlign: 'center'
  },
  progressContainer: {
    height: 3,
    backgroundColor: 'rgba(255, 255, 255, 0.3)'
  },
  progressBar: {
    height: '100%',
    backgroundColor: '#fff'
  },
  webView: {
    flex: 1,
    backgroundColor: '#fff'
  },
  errorContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff',
    padding: 20
  },
  errorTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#333',
    marginTop: 16,
    marginBottom: 8
  },
  errorMessage: {
    fontSize: 16,
    color: '#666',
    textAlign: 'center',
    marginBottom: 24
  },
  retryButton: {
    backgroundColor: '#007AFF',
    paddingHorizontal: 24,
    paddingVertical: 12,
    borderRadius: 8
  },
  retryButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600'
  }
});

export default PayTechWebView;
```

### src/components/TransactionStatus.js

```javascript
// src/components/TransactionStatus.js
import React from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  ActivityIndicator,
  StyleSheet
} from 'react-native';
import Icon from 'react-native-vector-icons/MaterialIcons';
import { useTransactionStatus } from '../hooks/useTransactionStatus';

const TransactionStatus = ({ refCommand, autoRefresh = false, onStatusChange }) => {
  const { 
    status, 
    loading, 
    error, 
    checkStatus,
    isCompleted,
    isPending,
    isFailed,
    isCancelled
  } = useTransactionStatus(refCommand, autoRefresh);

  React.useEffect(() => {
    if (status && onStatusChange) {
      onStatusChange(status);
    }
  }, [status, onStatusChange]);

  const getStatusColor = () => {
    if (isCompleted) return '#28a745';
    if (isPending) return '#ffc107';
    if (isFailed) return '#dc3545';
    if (isCancelled) return '#6c757d';
    return '#6c757d';
  };

  const getStatusIcon = () => {
    if (isCompleted) return 'check-circle';
    if (isPending) return 'schedule';
    if (isFailed) return 'error';
    if (isCancelled) return 'cancel';
    return 'help';
  };

  const getStatusText = () => {
    if (isCompleted) return '✅ Complété';
    if (isPending) return '⏳ En attente';
    if (isFailed) return '❌ Échoué';
    if (isCancelled) return '🚫 Annulé';
    return '❓ Inconnu';
  };

  const formatDate = (dateString) => {
    if (!dateString) return '';
    try {
      return new Date(dateString).toLocaleString('fr-FR');
    } catch (error) {
      return dateString;
    }
  };

  if (!refCommand) {
    return (
      <View style={styles.container}>
        <Text style={styles.noTransactionText}>
          Aucune transaction à vérifier
        </Text>
      </View>
    );
  }

  if (loading && !status) {
    return (
      <View style={styles.container}>
        <View style={styles.loadingContainer}>
          <ActivityIndicator size="large" color="#007AFF" />
          <Text style={styles.loadingText}>Vérification du statut...</Text>
        </View>
      </View>
    );
  }

  if (error && !status) {
    return (
      <View style={styles.container}>
        <View style={styles.errorContainer}>
          <Icon name="error-outline" size={48} color="#dc3545" />
          <Text style={styles.errorText}>Impossible de récupérer le statut</Text>
          <TouchableOpacity style={styles.retryButton} onPress={checkStatus}>
            <Text style={styles.retryButtonText}>Réessayer</Text>
          </TouchableOpacity>
        </View>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <View style={styles.card}>
        <Text style={styles.title}>Statut de la transaction</Text>
        
        {status && (
          <View style={styles.statusContainer}>
            <View style={styles.statusRow}>
              <Text style={styles.label}>Référence :</Text>
              <Text style={styles.refCommand}>{status.refCommand}</Text>
            </View>
            
            <View style={styles.statusRow}>
              <Text style={styles.label}>Statut :</Text>
              <View style={styles.statusBadge}>
                <Icon 
                  name={getStatusIcon()} 
                  size={16} 
                  color={getStatusColor()} 
                />
                <Text style={[styles.statusText, { color: getStatusColor() }]}>
                  {getStatusText()}
                </Text>
              </View>
            </View>
            
            <View style={styles.statusRow}>
              <Text style={styles.label}>Montant :</Text>
              <Text style={styles.amount}>{status.amount}</Text>
            </View>
            
            {status.completedAt && (
              <View style={styles.statusRow}>
                <Text style={styles.label}>Complété le :</Text>
                <Text style={styles.date}>
                  {formatDate(status.completedAt)}
                </Text>
              </View>
            )}
            
            <View style={styles.statusRow}>
              <Text style={styles.label}>Créé le :</Text>
              <Text style={styles.date}>
                {formatDate(status.createdAt)}
              </Text>
            </View>
          </View>
        )}

        <TouchableOpacity
          style={[styles.refreshButton, loading && styles.refreshButtonDisabled]}
          onPress={checkStatus}
          disabled={loading}
        >
          {loading ? (
            <View style={styles.refreshLoadingContainer}>
              <ActivityIndicator size="small" color="#fff" />
              <Text style={styles.refreshButtonText}>Vérification...</Text>
            </View>
          ) : (
            <View style={styles.refreshContainer}>
              <Icon name="refresh" size={20} color="#fff" />
              <Text style={styles.refreshButtonText}>Actualiser le statut</Text>
            </View>
          )}
        </TouchableOpacity>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    padding: 16
  },
  card: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 20,
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 2
    },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3
  },
  title: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 16,
    color: '#333'
  },
  statusContainer: {
    marginBottom: 20
  },
  statusRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12
  },
  label: {
    fontSize: 14,
    color: '#666',
    flex: 1
  },
  refCommand: {
    fontSize: 12,
    fontFamily: 'monospace',
    color: '#333',
    flex: 2,
    textAlign: 'right'
  },
  statusBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 2,
    justifyContent: 'flex-end'
  },
  statusText: {
    fontSize: 14,
    fontWeight: '600',
    marginLeft: 4
  },
  amount: {
    fontSize: 14,
    fontWeight: '600',
    color: '#333',
    flex: 2,
    textAlign: 'right'
  },
  date: {
    fontSize: 12,
    color: '#666',
    flex: 2,
    textAlign: 'right'
  },
  refreshButton: {
    backgroundColor: '#007AFF',
    borderRadius: 8,
    padding: 12,
    alignItems: 'center'
  },
  refreshButtonDisabled: {
    backgroundColor: '#ccc'
  },
  refreshContainer: {
    flexDirection: 'row',
    alignItems: 'center'
  },
  refreshLoadingContainer: {
    flexDirection: 'row',
    alignItems: 'center'
  },
  refreshButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600',
    marginLeft: 8
  },
  loadingContainer: {
    alignItems: 'center',
    padding: 40
  },
  loadingText: {
    marginTop: 16,
    fontSize: 16,
    color: '#666'
  },
  errorContainer: {
    alignItems: 'center',
    padding: 40
  },
  errorText: {
    fontSize: 16,
    color: '#dc3545',
    textAlign: 'center',
    marginTop: 16,
    marginBottom: 20
  },
  retryButton: {
    backgroundColor: '#007AFF',
    paddingHorizontal: 20,
    paddingVertical: 10,
    borderRadius: 8
  },
  retryButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600'
  },
  noTransactionText: {
    textAlign: 'center',
    fontSize: 16,
    color: '#666',
    padding: 40
  }
});

export default TransactionStatus;
```

## Écrans (Screens)

### src/screens/PaymentScreen.js

```javascript
// src/screens/PaymentScreen.js
import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  SafeAreaView,
  StatusBar,
  Alert
} from 'react-native';
import PaymentForm from '../components/PaymentForm';
import PayTechWebView from '../components/PayTechWebView';
import { PAYTECH_CONFIG } from '../config/paytech';

const PaymentScreen = ({ navigation }) => {
  const [showWebView, setShowWebView] = useState(false);
  const [redirectUrl, setRedirectUrl] = useState('');
  const [refCommand, setRefCommand] = useState('');

  const handlePaymentSuccess = (result) => {
    setRedirectUrl(result.redirectUrl);
    setRefCommand(result.transaction?.refCommand || '');
    setShowWebView(true);
  };

  const handlePaymentError = (error) => {
    Alert.alert('Erreur de paiement', error);
  };

  const handleWebViewSuccess = (url, refCommand) => {
    setShowWebView(false);
    navigation.navigate('PaymentResult', {
      result: 'success',
      refCommand: refCommand,
      returnUrl: url
    });
  };

  const handleWebViewCancel = (url, refCommand) => {
    setShowWebView(false);
    navigation.navigate('PaymentResult', {
      result: 'cancel',
      refCommand: refCommand,
      returnUrl: url
    });
  };

  const handleWebViewError = (url, refCommand) => {
    setShowWebView(false);
    navigation.navigate('PaymentResult', {
      result: 'error',
      refCommand: refCommand,
      returnUrl: url
    });
  };

  const handleWebViewClose = () => {
    setShowWebView(false);
  };

  if (showWebView) {
    return (
      <PayTechWebView
        redirectUrl={redirectUrl}
        refCommand={refCommand}
        onPaymentComplete={handleWebViewSuccess}
        onPaymentCancel={handleWebViewCancel}
        onPaymentError={handleWebViewError}
        onClose={handleWebViewClose}
      />
    );
  }

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#f5f5f5" />
      
      <View style={styles.header}>
        <Text style={styles.headerTitle}>Paiement PayTech</Text>
        <Text style={styles.headerSubtitle}>
          Effectuez vos paiements en toute sécurité
        </Text>
      </View>

      <PaymentForm
        onSuccess={handlePaymentSuccess}
        onError={handlePaymentError}
      />
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5'
  },
  header: {
    backgroundColor: '#fff',
    paddingHorizontal: 20,
    paddingVertical: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#e0e0e0'
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
    textAlign: 'center'
  },
  headerSubtitle: {
    fontSize: 14,
    color: '#666',
    textAlign: 'center',
    marginTop: 4
  }
});

export default PaymentScreen;
```

### src/screens/PaymentResultScreen.js

```javascript
// src/screens/PaymentResultScreen.js
import React, { useEffect } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  SafeAreaView,
  StatusBar,
  ScrollView
} from 'react-native';
import Icon from 'react-native-vector-icons/MaterialIcons';
import TransactionStatus from '../components/TransactionStatus';

const PaymentResultScreen = ({ route, navigation }) => {
  const { result, refCommand, returnUrl } = route.params;

  useEffect(() => {
    // Configurer la navigation header
    navigation.setOptions({
      title: getResultTitle(result),
      headerLeft: null, // Désactiver le bouton retour
      gestureEnabled: false // Désactiver le geste de retour
    });
  }, [navigation, result]);

  const getResultTitle = (result) => {
    switch (result) {
      case 'success': return 'Paiement réussi';
      case 'cancel': return 'Paiement annulé';
      case 'error': return 'Erreur de paiement';
      default: return 'Résultat du paiement';
    }
  };

  const getResultIcon = (result) => {
    switch (result) {
      case 'success': return 'check-circle';
      case 'cancel': return 'cancel';
      case 'error': return 'error';
      default: return 'help';
    }
  };

  const getResultColor = (result) => {
    switch (result) {
      case 'success': return '#28a745';
      case 'cancel': return '#ffc107';
      case 'error': return '#dc3545';
      default: return '#6c757d';
    }
  };

  const getResultMessage = (result) => {
    switch (result) {
      case 'success': 
        return 'Votre paiement a été initié avec succès. Veuillez patienter pendant que nous vérifions le statut.';
      case 'cancel': 
        return 'Votre paiement a été annulé. Aucun montant n\'a été débité.';
      case 'error': 
        return 'Une erreur est survenue lors du traitement de votre paiement.';
      default: 
        return 'Statut du paiement inconnu.';
    }
  };

  const handleNewPayment = () => {
    navigation.navigate('Payment');
  };

  const handleHome = () => {
    navigation.navigate('Home');
  };

  const handleStatusChange = (status) => {
    // Gérer les changements de statut si nécessaire
    console.log('Status changed:', status);
  };

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#f5f5f5" />
      
      <ScrollView style={styles.scrollView} showsVerticalScrollIndicator={false}>
        {/* Carte de résultat */}
        <View style={styles.resultCard}>
          <View style={[styles.iconContainer, { backgroundColor: getResultColor(result) + '20' }]}>
            <Icon 
              name={getResultIcon(result)} 
              size={64} 
              color={getResultColor(result)} 
            />
          </View>
          
          <Text style={styles.resultTitle}>{getResultTitle(result)}</Text>
          <Text style={styles.resultMessage}>{getResultMessage(result)}</Text>
          
          {refCommand && (
            <View style={styles.refCommandContainer}>
              <Text style={styles.refCommandLabel}>Référence de commande</Text>
              <Text style={styles.refCommandText}>{refCommand}</Text>
            </View>
          )}
        </View>

        {/* Statut de la transaction */}
        {refCommand && (
          <TransactionStatus
            refCommand={refCommand}
            autoRefresh={result === 'success'}
            onStatusChange={handleStatusChange}
          />
        )}

        {/* Boutons d'action */}
        <View style={styles.actionsContainer}>
          <TouchableOpacity style={styles.primaryButton} onPress={handleNewPayment}>
            <Icon name="payment" size={20} color="#fff" />
            <Text style={styles.primaryButtonText}>Nouveau paiement</Text>
          </TouchableOpacity>
          
          <TouchableOpacity style={styles.secondaryButton} onPress={handleHome}>
            <Icon name="home" size={20} color="#007AFF" />
            <Text style={styles.secondaryButtonText}>Retour à l'accueil</Text>
          </TouchableOpacity>
        </View>
      </ScrollView>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5'
  },
  scrollView: {
    flex: 1
  },
  resultCard: {
    backgroundColor: '#fff',
    margin: 16,
    borderRadius: 16,
    padding: 24,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 4
    },
    shadowOpacity: 0.1,
    shadowRadius: 8,
    elevation: 4
  },
  iconContainer: {
    width: 96,
    height: 96,
    borderRadius: 48,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 20
  },
  resultTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
    textAlign: 'center',
    marginBottom: 12
  },
  resultMessage: {
    fontSize: 16,
    color: '#666',
    textAlign: 'center',
    lineHeight: 24,
    marginBottom: 20
  },
  refCommandContainer: {
    backgroundColor: '#f8f9fa',
    borderRadius: 8,
    padding: 16,
    width: '100%',
    alignItems: 'center'
  },
  refCommandLabel: {
    fontSize: 12,
    color: '#666',
    marginBottom: 4
  },
  refCommandText: {
    fontSize: 16,
    fontFamily: 'monospace',
    color: '#333',
    fontWeight: '600'
  },
  actionsContainer: {
    padding: 16,
    paddingBottom: 32
  },
  primaryButton: {
    backgroundColor: '#007AFF',
    borderRadius: 12,
    padding: 16,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 12
  },
  primaryButtonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
    marginLeft: 8
  },
  secondaryButton: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 2,
    borderColor: '#007AFF'
  },
  secondaryButtonText: {
    color: '#007AFF',
    fontSize: 16,
    fontWeight: '600',
    marginLeft: 8
  }
});

export default PaymentResultScreen;
```

## Navigation

### src/navigation/AppNavigator.js

```javascript
// src/navigation/AppNavigator.js
import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import { Linking } from 'react-native';

// Screens
import HomeScreen from '../screens/HomeScreen';
import PaymentScreen from '../screens/PaymentScreen';
import PaymentResultScreen from '../screens/PaymentResultScreen';
import TransactionHistoryScreen from '../screens/TransactionHistoryScreen';

const Stack = createStackNavigator();

const AppNavigator = () => {
  // Configuration des liens profonds
  const linking = {
    prefixes: ['paytech://'],
    config: {
      screens: {
        PaymentResult: {
          path: 'payment/:result',
          parse: {
            result: (result) => result,
          },
        },
      },
    },
  };

  return (
    <NavigationContainer linking={linking}>
      <Stack.Navigator
        initialRouteName="Home"
        screenOptions={{
          headerStyle: {
            backgroundColor: '#007AFF',
          },
          headerTintColor: '#fff',
          headerTitleStyle: {
            fontWeight: 'bold',
          },
        }}
      >
        <Stack.Screen
          name="Home"
          component={HomeScreen}
          options={{
            title: 'PayTech App',
            headerStyle: {
              backgroundColor: '#007AFF',
            },
          }}
        />
        
        <Stack.Screen
          name="Payment"
          component={PaymentScreen}
          options={{
            title: 'Nouveau paiement',
            headerBackTitleVisible: false,
          }}
        />
        
        <Stack.Screen
          name="PaymentResult"
          component={PaymentResultScreen}
          options={{
            headerBackTitleVisible: false,
            gestureEnabled: false,
          }}
        />
        
        <Stack.Screen
          name="TransactionHistory"
          component={TransactionHistoryScreen}
          options={{
            title: 'Historique des transactions',
            headerBackTitleVisible: false,
          }}
        />
      </Stack.Navigator>
    </NavigationContainer>
  );
};

export default AppNavigator;
```

## Configuration principale

### App.js

```javascript
// App.js
import React, { useEffect } from 'react';
import { StatusBar, Platform } from 'react-native';
import { enableScreens } from 'react-native-screens';
import Orientation from 'react-native-orientation-locker';
import AppNavigator from './src/navigation/AppNavigator';
import { PAYTECH_CONFIG } from './src/config/paytech';

// Optimisation des performances
enableScreens();

const App = () => {
  useEffect(() => {
    // Configuration de l'orientation
    if (PAYTECH_CONFIG.MOBILE_CONFIG.orientationLock === 'portrait') {
      Orientation.lockToPortrait();
    }

    // Configuration de la barre de statut
    if (Platform.OS === 'android') {
      StatusBar.setBackgroundColor('#007AFF', true);
      StatusBar.setBarStyle('light-content', true);
    }

    return () => {
      Orientation.unlockAllOrientations();
    };
  }, []);

  return (
    <>
      <StatusBar
        barStyle={Platform.OS === 'ios' ? 'dark-content' : 'light-content'}
        backgroundColor="#007AFF"
      />
      <AppNavigator />
    </>
  );
};

export default App;
```

### metro.config.js

```javascript
// metro.config.js
const { getDefaultConfig } = require('metro-config');

module.exports = (async () => {
  const {
    resolver: { sourceExts, assetExts },
  } = await getDefaultConfig();
  
  return {
    transformer: {
      getTransformOptions: async () => ({
        transform: {
          experimentalImportSupport: false,
          inlineRequires: true,
        },
      }),
    },
    resolver: {
      assetExts: assetExts.filter(ext => ext !== 'svg'),
      sourceExts: [...sourceExts, 'svg'],
    },
  };
})();
```

## Tests

### __tests__/PaymentForm.test.js

```javascript
// __tests__/PaymentForm.test.js
import React from 'react';
import { render, fireEvent, waitFor } from '@testing-library/react-native';
import PaymentForm from '../src/components/PaymentForm';

// Mock des hooks
jest.mock('../src/hooks/usePayment', () => ({
  usePayment: () => ({
    loading: false,
    error: null,
    initiatePayment: jest.fn(),
    clearMessages: jest.fn()
  })
}));

describe('PaymentForm', () => {
  it('renders correctly', () => {
    const { getByText, getByPlaceholderText } = render(<PaymentForm />);
    
    expect(getByText('Paiement PayTech')).toBeTruthy();
    expect(getByPlaceholderText('iPhone 13 Pro')).toBeTruthy();
    expect(getByPlaceholderText('650000')).toBeTruthy();
  });

  it('validates required fields', async () => {
    const onError = jest.fn();
    const { getByText } = render(<PaymentForm onError={onError} />);
    
    const payButton = getByText('Payer avec PayTech');
    fireEvent.press(payButton);
    
    await waitFor(() => {
      expect(onError).toHaveBeenCalledWith(
        expect.stringContaining('champs obligatoires')
      );
    });
  });

  it('validates minimum amount', async () => {
    const onError = jest.fn();
    const { getByPlaceholderText, getByText } = render(<PaymentForm onError={onError} />);
    
    fireEvent.changeText(getByPlaceholderText('iPhone 13 Pro'), 'Test Product');
    fireEvent.changeText(getByPlaceholderText('650000'), '50');
    
    const payButton = getByText('Payer avec PayTech');
    fireEvent.press(payButton);
    
    await waitFor(() => {
      expect(onError).toHaveBeenCalledWith(
        expect.stringContaining('minimum')
      );
    });
  });
});
```

## Bonnes pratiques React Native

### Performance

1. **FlatList** pour les listes importantes
2. **Image caching** avec react-native-fast-image
3. **Bundle splitting** avec Metro
4. **Memory management** avec hooks
5. **Native modules** pour les opérations critiques

### Sécurité

1. **HTTPS obligatoire** pour toutes les communications
2. **Certificate pinning** avec react-native-ssl-pinning
3. **Keychain/Keystore** pour le stockage sécurisé
4. **Code obfuscation** en production
5. **Deep link validation** pour les URL schemes

### UX/UI

1. **Platform-specific** design avec Platform.select
2. **Responsive design** avec Dimensions et flexbox
3. **Accessibility** avec accessibilityLabel
4. **Loading states** et error boundaries
5. **Offline support** avec NetInfo

### Architecture

1. **Hooks pattern** pour la logique réutilisable
2. **Context API** pour l'état global
3. **Custom hooks** pour les services
4. **Component composition** pour la réutilisabilité
5. **Error boundaries** pour la gestion d'erreurs

Cette intégration React Native PayTech offre une solution cross-platform complète avec WebView sécurisée, hooks personnalisés, navigation moderne, et une expérience utilisateur optimale pour iOS et Android.


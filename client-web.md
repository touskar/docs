# Client Web - Intégration PayTech

Cette section détaille l'intégration de PayTech côté client web, incluant les bibliothèques JavaScript, les frameworks modernes, et les bonnes pratiques pour une expérience utilisateur optimale.

## Vue d'ensemble

L'intégration côté client PayTech permet de :

- **Créer des interfaces** de paiement modernes et responsives
- **Gérer les redirections** vers les plateformes de paiement
- **Traiter les retours** de paiement (succès/annulation)
- **Préfiller les formulaires** avec les données utilisateur
- **Optimiser l'UX** pour mobile et desktop

## JavaScript Vanilla

### Configuration de base

```javascript
// Configuration PayTech
const PAYTECH_CONFIG = {
    API_KEY: 'votre_api_key_publique',
    BASE_URL: 'https://paytech.sn/api',
    ENVIRONMENT: 'prod', // ou 'test'
    MOBILE_SUCCESS_URL: 'https://paytech.sn/mobile/success',
    MOBILE_CANCEL_URL: 'https://paytech.sn/mobile/cancel'
};

// Détection de l'environnement mobile
const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
```

### Classe PayTechClient

```javascript
class PayTechClient {
    constructor(config) {
        this.config = config;
        this.isProcessing = false;
    }

    /**
     * Initier un paiement
     */
    async initiatePayment(paymentData) {
        if (this.isProcessing) {
            throw new Error('Un paiement est déjà en cours');
        }

        this.isProcessing = true;
        
        try {
            // Validation des données
            this.validatePaymentData(paymentData);
            
            // Préparation des données pour l'API
            const requestData = this.preparePaymentRequest(paymentData);
            
            // Appel à votre backend
            const response = await this.callBackendAPI('/payment/request', requestData);
            
            if (response.success) {
                // Redirection vers PayTech
                this.redirectToPayment(response.data.redirectUrl, paymentData.user);
            } else {
                throw new Error(response.errors.join(', '));
            }
            
        } catch (error) {
            this.handlePaymentError(error);
            throw error;
        } finally {
            this.isProcessing = false;
        }
    }

    /**
     * Validation des données de paiement
     */
    validatePaymentData(data) {
        const required = ['item_name', 'item_price', 'ref_command'];
        
        for (const field of required) {
            if (!data[field]) {
                throw new Error(`Le champ ${field} est requis`);
            }
        }
        
        if (data.item_price <= 0) {
            throw new Error('Le montant doit être supérieur à 0');
        }
        
        if (data.payment_method && !this.isValidPaymentMethod(data.payment_method)) {
            throw new Error('Méthode de paiement non supportée');
        }
    }

    /**
     * Préparation de la requête de paiement
     */
    preparePaymentRequest(data) {
        const baseRequest = {
            item_name: data.item_name,
            item_price: data.item_price,
            currency: data.currency || 'XOF',
            ref_command: data.ref_command,
            command_name: data.command_name || data.item_name,
            env: this.config.ENVIRONMENT,
            custom_field: JSON.stringify(data.custom_field || {}),
            success_url: data.success_url || window.location.origin + '/payment/success',
            cancel_url: data.cancel_url || window.location.origin + '/payment/cancel',
            ipn_url: data.ipn_url
        };

        // Configuration spéciale pour mobile
        if (isMobile && data.mobile_integration) {
            baseRequest.success_url = this.config.MOBILE_SUCCESS_URL;
            baseRequest.cancel_url = this.config.MOBILE_CANCEL_URL;
        }

        // Méthode de paiement spécifique
        if (data.payment_method) {
            baseRequest.target_payment = data.payment_method;
        }

        return baseRequest;
    }

    /**
     * Redirection vers PayTech avec préfillage
     */
    redirectToPayment(redirectUrl, user = null) {
        let finalUrl = redirectUrl;

        // Préfillage des données utilisateur
        if (user && user.phone_number) {
            const params = new URLSearchParams();
            
            // Numéro complet avec indicatif
            params.append('pn', user.phone_number);
            
            // Numéro national (sans indicatif)
            if (user.phone_number.startsWith('+221')) {
                params.append('nn', user.phone_number.substring(4));
            }
            
            // Nom complet
            if (user.first_name && user.last_name) {
                params.append('fn', `${user.first_name} ${user.last_name}`);
            }
            
            // Méthode de paiement ciblée
            if (user.preferred_payment_method) {
                params.append('tp', user.preferred_payment_method);
            }
            
            // Auto-submit (1 = oui, 0 = non)
            params.append('nac', user.auto_submit ? '1' : '0');
            
            finalUrl += (finalUrl.includes('?') ? '&' : '?') + params.toString();
        }

        // Redirection
        if (isMobile && window.ReactNativeWebView) {
            // Pour React Native WebView
            window.ReactNativeWebView.postMessage(JSON.stringify({
                type: 'PAYMENT_REDIRECT',
                url: finalUrl
            }));
        } else if (window.flutter_inappwebview) {
            // Pour Flutter WebView
            window.flutter_inappwebview.callHandler('paymentRedirect', finalUrl);
        } else {
            // Redirection web classique
            window.location.href = finalUrl;
        }
    }

    /**
     * Appel à l'API backend
     */
    async callBackendAPI(endpoint, data) {
        const response = await fetch(`/api${endpoint}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.getCSRFToken()
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        return await response.json();
    }

    /**
     * Gestion des erreurs de paiement
     */
    handlePaymentError(error) {
        console.error('Erreur de paiement:', error);
        
        // Affichage à l'utilisateur
        this.showErrorMessage(error.message);
        
        // Tracking d'erreur (optionnel)
        if (window.gtag) {
            gtag('event', 'payment_error', {
                error_message: error.message,
                timestamp: new Date().toISOString()
            });
        }
    }

    /**
     * Affichage des messages d'erreur
     */
    showErrorMessage(message) {
        // Implémentation selon votre UI
        const errorDiv = document.getElementById('payment-error');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        } else {
            alert(message); // Fallback
        }
    }

    /**
     * Récupération du token CSRF
     */
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    /**
     * Validation des méthodes de paiement
     */
    isValidPaymentMethod(method) {
        const validMethods = [
            'Orange Money', 'Tigo Cash', 'Wave', 'PayExpresse',
            'Carte Bancaire', 'Wari', 'Poste Cash', 'PayPal',
            'Emoney', 'Joni Joni'
        ];
        return validMethods.includes(method);
    }
}
```

### Utilisation de la classe PayTechClient

```javascript
// Initialisation
const paytech = new PayTechClient(PAYTECH_CONFIG);

// Exemple d'utilisation
document.getElementById('pay-button').addEventListener('click', async function() {
    const paymentData = {
        item_name: 'iPhone 13 Pro',
        item_price: 650000, // en XOF
        ref_command: 'CMD_' + Date.now(),
        command_name: 'Achat iPhone 13 Pro 256GB',
        payment_method: 'Orange Money', // Optionnel
        user: {
            phone_number: '+221771234567',
            first_name: 'John',
            last_name: 'Doe',
            preferred_payment_method: 'Orange Money',
            auto_submit: true
        },
        custom_field: {
            user_id: 12345,
            plan: 'premium'
        },
        mobile_integration: true // Pour les apps mobiles
    };

    try {
        await paytech.initiatePayment(paymentData);
    } catch (error) {
        console.error('Erreur lors du paiement:', error);
    }
});
```

## Intégration React

### Hook personnalisé usePayTech

```jsx
import { useState, useCallback } from 'react';

const usePayTech = (config) => {
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState(null);

    const initiatePayment = useCallback(async (paymentData) => {
        setIsLoading(true);
        setError(null);

        try {
            const response = await fetch('/api/payment/request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(paymentData)
            });

            const result = await response.json();

            if (result.success) {
                // Redirection vers PayTech
                window.location.href = result.data.redirectUrl;
            } else {
                throw new Error(result.errors.join(', '));
            }
        } catch (err) {
            setError(err.message);
        } finally {
            setIsLoading(false);
        }
    }, []);

    return { initiatePayment, isLoading, error };
};

export default usePayTech;
```

### Composant PaymentButton

```jsx
import React, { useState } from 'react';
import usePayTech from './hooks/usePayTech';

const PaymentButton = ({ 
    item, 
    user, 
    onSuccess, 
    onError,
    className = '',
    children = 'Payer maintenant'
}) => {
    const { initiatePayment, isLoading, error } = usePayTech();
    const [selectedMethod, setSelectedMethod] = useState('');

    const paymentMethods = [
        { id: 'Orange Money', name: 'Orange Money', icon: '🟠' },
        { id: 'Wave', name: 'Wave', icon: '🌊' },
        { id: 'Tigo Cash', name: 'Tigo Cash', icon: '🔵' },
        { id: 'Carte Bancaire', name: 'Carte Bancaire', icon: '💳' }
    ];

    const handlePayment = async () => {
        const paymentData = {
            item_name: item.name,
            item_price: item.price,
            ref_command: `CMD_${Date.now()}_${user.id}`,
            command_name: `Achat ${item.name}`,
            payment_method: selectedMethod || undefined,
            user: {
                phone_number: user.phone,
                first_name: user.firstName,
                last_name: user.lastName,
                auto_submit: true
            },
            custom_field: {
                user_id: user.id,
                item_id: item.id
            }
        };

        try {
            await initiatePayment(paymentData);
            onSuccess && onSuccess();
        } catch (err) {
            onError && onError(err);
        }
    };

    return (
        <div className="paytech-payment-component">
            {/* Sélection de méthode de paiement */}
            <div className="payment-methods mb-4">
                <h4 className="text-lg font-semibold mb-2">
                    Choisir une méthode de paiement
                </h4>
                <div className="grid grid-cols-2 gap-2">
                    {paymentMethods.map(method => (
                        <button
                            key={method.id}
                            type="button"
                            className={`p-3 border rounded-lg flex items-center space-x-2 ${
                                selectedMethod === method.id 
                                    ? 'border-blue-500 bg-blue-50' 
                                    : 'border-gray-300'
                            }`}
                            onClick={() => setSelectedMethod(method.id)}
                        >
                            <span>{method.icon}</span>
                            <span>{method.name}</span>
                        </button>
                    ))}
                </div>
            </div>

            {/* Bouton de paiement */}
            <button
                onClick={handlePayment}
                disabled={isLoading}
                className={`w-full py-3 px-6 bg-blue-600 text-white rounded-lg font-semibold 
                    ${isLoading ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700'} 
                    ${className}`}
            >
                {isLoading ? (
                    <div className="flex items-center justify-center space-x-2">
                        <div className="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                        <span>Traitement...</span>
                    </div>
                ) : (
                    children
                )}
            </button>

            {/* Affichage des erreurs */}
            {error && (
                <div className="mt-3 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    {error}
                </div>
            )}

            {/* Informations de sécurité */}
            <div className="mt-4 text-sm text-gray-600 text-center">
                <p>🔒 Paiement sécurisé par PayTech</p>
                <p>Vos données sont protégées</p>
            </div>
        </div>
    );
};

export default PaymentButton;
```

### Utilisation du composant React

```jsx
import React from 'react';
import PaymentButton from './components/PaymentButton';

const ProductPage = () => {
    const item = {
        id: 1,
        name: 'iPhone 13 Pro',
        price: 650000,
        description: 'iPhone 13 Pro 256GB Bleu Alpin'
    };

    const user = {
        id: 12345,
        firstName: 'John',
        lastName: 'Doe',
        phone: '+221771234567'
    };

    const handlePaymentSuccess = () => {
        console.log('Redirection vers PayTech réussie');
        // Analytics, tracking, etc.
    };

    const handlePaymentError = (error) => {
        console.error('Erreur de paiement:', error);
        // Gestion d'erreur, notification utilisateur
    };

    return (
        <div className="max-w-md mx-auto p-6">
            <div className="bg-white rounded-lg shadow-lg p-6">
                <h2 className="text-2xl font-bold mb-4">{item.name}</h2>
                <p className="text-gray-600 mb-4">{item.description}</p>
                <p className="text-3xl font-bold text-blue-600 mb-6">
                    {item.price.toLocaleString()} FCFA
                </p>
                
                <PaymentButton
                    item={item}
                    user={user}
                    onSuccess={handlePaymentSuccess}
                    onError={handlePaymentError}
                />
            </div>
        </div>
    );
};

export default ProductPage;
```

## Intégration Vue.js

### Composable usePayTech (Vue 3)

```javascript
import { ref, reactive } from 'vue';

export function usePayTech() {
    const isLoading = ref(false);
    const error = ref(null);
    
    const state = reactive({
        currentPayment: null,
        paymentHistory: []
    });

    const initiatePayment = async (paymentData) => {
        isLoading.value = true;
        error.value = null;

        try {
            const response = await fetch('/api/payment/request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify(paymentData)
            });

            const result = await response.json();

            if (result.success) {
                state.currentPayment = {
                    ...paymentData,
                    redirectUrl: result.data.redirectUrl,
                    timestamp: new Date()
                };
                
                // Redirection
                window.location.href = result.data.redirectUrl;
                
                return result;
            } else {
                throw new Error(result.errors.join(', '));
            }
        } catch (err) {
            error.value = err.message;
            throw err;
        } finally {
            isLoading.value = false;
        }
    };

    const addToHistory = (payment) => {
        state.paymentHistory.unshift(payment);
    };

    return {
        isLoading,
        error,
        state,
        initiatePayment,
        addToHistory
    };
}
```

### Composant PaymentForm Vue

```vue
<template>
  <div class="paytech-payment-form">
    <div class="payment-summary mb-6">
      <h3 class="text-xl font-semibold mb-2">Résumé de la commande</h3>
      <div class="bg-gray-50 p-4 rounded-lg">
        <div class="flex justify-between items-center">
          <span>{{ item.name }}</span>
          <span class="font-bold">{{ formatPrice(item.price) }} FCFA</span>
        </div>
      </div>
    </div>

    <div class="payment-methods mb-6">
      <h4 class="text-lg font-semibold mb-3">Méthode de paiement</h4>
      <div class="space-y-2">
        <label 
          v-for="method in paymentMethods" 
          :key="method.id"
          class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
          :class="{ 'border-blue-500 bg-blue-50': selectedMethod === method.id }"
        >
          <input 
            type="radio" 
            :value="method.id" 
            v-model="selectedMethod"
            class="mr-3"
          >
          <span class="mr-2">{{ method.icon }}</span>
          <span>{{ method.name }}</span>
        </label>
      </div>
    </div>

    <div class="user-info mb-6" v-if="showUserInfo">
      <h4 class="text-lg font-semibold mb-3">Informations de facturation</h4>
      <div class="grid grid-cols-2 gap-4">
        <input 
          v-model="userInfo.firstName"
          type="text" 
          placeholder="Prénom"
          class="p-3 border rounded-lg"
        >
        <input 
          v-model="userInfo.lastName"
          type="text" 
          placeholder="Nom"
          class="p-3 border rounded-lg"
        >
        <input 
          v-model="userInfo.phone"
          type="tel" 
          placeholder="+221 77 123 45 67"
          class="p-3 border rounded-lg col-span-2"
        >
      </div>
    </div>

    <button 
      @click="handlePayment"
      :disabled="isLoading || !isFormValid"
      class="w-full py-4 px-6 bg-blue-600 text-white rounded-lg font-semibold text-lg
             disabled:opacity-50 disabled:cursor-not-allowed hover:bg-blue-700"
    >
      <div v-if="isLoading" class="flex items-center justify-center space-x-2">
        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
        <span>Traitement en cours...</span>
      </div>
      <span v-else>
        Payer {{ formatPrice(item.price) }} FCFA
      </span>
    </button>

    <div v-if="error" class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
      {{ error }}
    </div>

    <div class="mt-6 text-center text-sm text-gray-600">
      <p>🔒 Paiement 100% sécurisé</p>
      <p>Propulsé par PayTech Sénégal</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive } from 'vue';
import { usePayTech } from '@/composables/usePayTech';

const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  user: {
    type: Object,
    default: () => ({})
  },
  showUserInfo: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['success', 'error']);

const { isLoading, error, initiatePayment } = usePayTech();

const selectedMethod = ref('');
const userInfo = reactive({
  firstName: props.user.firstName || '',
  lastName: props.user.lastName || '',
  phone: props.user.phone || ''
});

const paymentMethods = [
  { id: 'Orange Money', name: 'Orange Money', icon: '🟠' },
  { id: 'Wave', name: 'Wave', icon: '🌊' },
  { id: 'Tigo Cash', name: 'Tigo Cash', icon: '🔵' },
  { id: 'Carte Bancaire', name: 'Carte Bancaire', icon: '💳' },
  { id: 'PayExpresse', name: 'PayExpresse', icon: '💰' }
];

const isFormValid = computed(() => {
  if (props.showUserInfo) {
    return selectedMethod.value && 
           userInfo.firstName && 
           userInfo.lastName && 
           userInfo.phone;
  }
  return selectedMethod.value;
});

const formatPrice = (price) => {
  return new Intl.NumberFormat('fr-FR').format(price);
};

const handlePayment = async () => {
  const paymentData = {
    item_name: props.item.name,
    item_price: props.item.price,
    ref_command: `CMD_${Date.now()}_${props.user.id || 'guest'}`,
    command_name: `Achat ${props.item.name}`,
    payment_method: selectedMethod.value,
    user: {
      phone_number: userInfo.phone || props.user.phone,
      first_name: userInfo.firstName || props.user.firstName,
      last_name: userInfo.lastName || props.user.lastName,
      auto_submit: true
    },
    custom_field: {
      user_id: props.user.id,
      item_id: props.item.id,
      source: 'web'
    }
  };

  try {
    await initiatePayment(paymentData);
    emit('success', paymentData);
  } catch (err) {
    emit('error', err);
  }
};
</script>
```

## Gestion des retours de paiement

### Page de succès

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement réussi - PayTech</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
            <div class="mb-6">
                <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-4">
                Paiement réussi !
            </h1>
            
            <p class="text-gray-600 mb-6">
                Votre paiement a été traité avec succès. Vous recevrez une confirmation par email.
            </p>
            
            <div class="space-y-3">
                <button onclick="window.close()" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700">
                    Fermer
                </button>
                <a href="/" class="block w-full bg-gray-200 text-gray-800 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300">
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <script>
        // Notification au parent (pour les webviews)
        if (window.parent !== window) {
            window.parent.postMessage({
                type: 'PAYMENT_SUCCESS',
                timestamp: new Date().toISOString()
            }, '*');
        }

        // Pour React Native
        if (window.ReactNativeWebView) {
            window.ReactNativeWebView.postMessage(JSON.stringify({
                type: 'PAYMENT_SUCCESS'
            }));
        }

        // Pour Flutter
        if (window.flutter_inappwebview) {
            window.flutter_inappwebview.callHandler('paymentSuccess');
        }
    </script>
</body>
</html>
```

### Page d'annulation

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement annulé - PayTech</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
            <div class="mb-6">
                <div class="mx-auto w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-4">
                Paiement annulé
            </h1>
            
            <p class="text-gray-600 mb-6">
                Votre paiement a été annulé. Aucun montant n'a été débité.
            </p>
            
            <div class="space-y-3">
                <button onclick="history.back()" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700">
                    Réessayer le paiement
                </button>
                <a href="/" class="block w-full bg-gray-200 text-gray-800 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300">
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <script>
        // Notification au parent
        if (window.parent !== window) {
            window.parent.postMessage({
                type: 'PAYMENT_CANCELLED',
                timestamp: new Date().toISOString()
            }, '*');
        }

        // Pour React Native
        if (window.ReactNativeWebView) {
            window.ReactNativeWebView.postMessage(JSON.stringify({
                type: 'PAYMENT_CANCELLED'
            }));
        }

        // Pour Flutter
        if (window.flutter_inappwebview) {
            window.flutter_inappwebview.callHandler('paymentCancelled');
        }
    </script>
</body>
</html>
```

## Bonnes pratiques côté client

### Sécurité

1. **Ne jamais exposer** les clés secrètes côté client
2. **Valider les données** avant envoi au backend
3. **Utiliser HTTPS** pour toutes les communications
4. **Implémenter un timeout** pour les requêtes
5. **Gérer les erreurs** de manière appropriée

### Performance

1. **Lazy loading** des composants de paiement
2. **Mise en cache** des méthodes de paiement
3. **Optimisation** des images et icônes
4. **Compression** des assets JavaScript
5. **CDN** pour les ressources statiques

### UX/UI

1. **Feedback visuel** pendant le traitement
2. **Messages d'erreur** clairs et utiles
3. **Interface responsive** pour mobile
4. **Accessibilité** (ARIA labels, contraste)
5. **Tests** sur différents navigateurs

### Monitoring

1. **Analytics** sur les conversions
2. **Tracking** des erreurs JavaScript
3. **Métriques** de performance
4. **A/B testing** des interfaces
5. **Logs** des interactions utilisateur

L'intégration côté client PayTech offre une flexibilité maximale pour créer des expériences de paiement modernes et optimisées, que ce soit en JavaScript vanilla, React, Vue.js ou d'autres frameworks.


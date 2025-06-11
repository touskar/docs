# React - Intégration PayTech

Cette section détaille l'intégration de PayTech dans une application React, incluant les composants, hooks personnalisés, et la gestion des paiements côté client.

## Vue d'ensemble

React offre un écosystème moderne pour intégrer PayTech avec :

- **Composants réutilisables** pour les formulaires de paiement
- **Hooks personnalisés** pour la gestion d'état
- **Context API** pour la configuration globale
- **Axios/Fetch** pour les appels API
- **React Router** pour la navigation

## Installation et configuration

### Prérequis

- **Node.js** : Version 16 ou supérieure
- **React** : Version 18+ recommandée
- **TypeScript** : Optionnel mais recommandé

### Installation des dépendances

```bash
npm install axios react-router-dom
# ou avec yarn
yarn add axios react-router-dom

# Pour TypeScript (optionnel)
npm install -D @types/react @types/react-dom typescript
```

### Configuration de base

Créez un fichier de configuration `src/config/paytech.js` :

```javascript
// src/config/paytech.js
export const PAYTECH_CONFIG = {
  // URLs de votre backend
  API_BASE_URL: process.env.REACT_APP_API_URL || 'http://localhost:3001/api',
  
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
  
  // URLs de retour (gérées par votre backend)
  RETURN_URLS: {
    success: '/payment/success',
    cancel: '/payment/cancel',
    error: '/payment/error'
  },
  
  // Configuration mobile
  MOBILE_URLS: {
    success: 'https://paytech.sn/mobile/success',
    cancel: 'https://paytech.sn/mobile/cancel'
  }
};
```

## Context PayTech

### PayTechContext.js

```javascript
// src/context/PayTechContext.js
import React, { createContext, useContext, useReducer } from 'react';
import { PAYTECH_CONFIG } from '../config/paytech';

const PayTechContext = createContext();

// Actions
const ACTIONS = {
  SET_LOADING: 'SET_LOADING',
  SET_ERROR: 'SET_ERROR',
  SET_SUCCESS: 'SET_SUCCESS',
  RESET_STATE: 'RESET_STATE',
  SET_TRANSACTION: 'SET_TRANSACTION'
};

// Reducer
const payTechReducer = (state, action) => {
  switch (action.type) {
    case ACTIONS.SET_LOADING:
      return {
        ...state,
        loading: action.payload,
        error: null
      };
    case ACTIONS.SET_ERROR:
      return {
        ...state,
        loading: false,
        error: action.payload
      };
    case ACTIONS.SET_SUCCESS:
      return {
        ...state,
        loading: false,
        error: null,
        success: action.payload
      };
    case ACTIONS.SET_TRANSACTION:
      return {
        ...state,
        currentTransaction: action.payload
      };
    case ACTIONS.RESET_STATE:
      return {
        ...initialState
      };
    default:
      return state;
  }
};

// État initial
const initialState = {
  loading: false,
  error: null,
  success: null,
  currentTransaction: null
};

// Provider
export const PayTechProvider = ({ children }) => {
  const [state, dispatch] = useReducer(payTechReducer, initialState);

  const value = {
    ...state,
    dispatch,
    actions: ACTIONS,
    config: PAYTECH_CONFIG
  };

  return (
    <PayTechContext.Provider value={value}>
      {children}
    </PayTechContext.Provider>
  );
};

// Hook personnalisé
export const usePayTech = () => {
  const context = useContext(PayTechContext);
  if (!context) {
    throw new Error('usePayTech must be used within a PayTechProvider');
  }
  return context;
};
```

## Service API PayTech

### payTechService.js

```javascript
// src/services/payTechService.js
import axios from 'axios';
import { PAYTECH_CONFIG } from '../config/paytech';

// Configuration Axios
const api = axios.create({
  baseURL: PAYTECH_CONFIG.API_BASE_URL,
  timeout: 30000,
  headers: {
    'Content-Type': 'application/json'
  }
});

// Intercepteur pour les erreurs
api.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error('PayTech API Error:', error);
    return Promise.reject(error);
  }
);

export const payTechService = {
  /**
   * Créer une demande de paiement
   */
  async requestPayment(paymentData) {
    try {
      const response = await api.post('/payment/request', paymentData);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      return {
        success: false,
        error: error.response?.data?.message || error.message || 'Erreur de paiement'
      };
    }
  },

  /**
   * Vérifier le statut d'une transaction
   */
  async checkTransactionStatus(refCommand) {
    try {
      const response = await api.get(`/payment/status/${refCommand}`);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      return {
        success: false,
        error: error.response?.data?.message || error.message || 'Erreur de vérification'
      };
    }
  },

  /**
   * Obtenir l'historique des transactions d'un utilisateur
   */
  async getUserTransactions(userId) {
    try {
      const response = await api.get(`/payment/history/${userId}`);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      return {
        success: false,
        error: error.response?.data?.message || error.message || 'Erreur de récupération'
      };
    }
  }
};
```

## Hook personnalisé usePayment

### usePayment.js

```javascript
// src/hooks/usePayment.js
import { useState, useCallback } from 'react';
import { usePayTech } from '../context/PayTechContext';
import { payTechService } from '../services/payTechService';

export const usePayment = () => {
  const { dispatch, actions } = usePayTech();
  const [paymentState, setPaymentState] = useState({
    processing: false,
    redirectUrl: null,
    transaction: null
  });

  /**
   * Initier un paiement
   */
  const initiatePayment = useCallback(async (paymentData) => {
    try {
      dispatch({ type: actions.SET_LOADING, payload: true });
      setPaymentState(prev => ({ ...prev, processing: true }));

      // Génération de la référence de commande si non fournie
      if (!paymentData.refCommand) {
        paymentData.refCommand = `CMD_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
      }

      // Appel au service PayTech
      const result = await payTechService.requestPayment(paymentData);

      if (result.success) {
        const transaction = result.data.transaction;
        const redirectUrl = result.data.data.redirect_url;

        setPaymentState({
          processing: false,
          redirectUrl,
          transaction
        });

        dispatch({ type: actions.SET_TRANSACTION, payload: transaction });
        dispatch({ type: actions.SET_SUCCESS, payload: 'Redirection vers PayTech...' });

        // Redirection automatique vers PayTech
        if (redirectUrl) {
          window.location.href = redirectUrl;
        }

        return { success: true, redirectUrl, transaction };
      } else {
        throw new Error(result.error);
      }
    } catch (error) {
      setPaymentState(prev => ({ ...prev, processing: false }));
      dispatch({ type: actions.SET_ERROR, payload: error.message });
      return { success: false, error: error.message };
    }
  }, [dispatch, actions]);

  /**
   * Vérifier le statut d'un paiement
   */
  const checkPaymentStatus = useCallback(async (refCommand) => {
    try {
      dispatch({ type: actions.SET_LOADING, payload: true });

      const result = await payTechService.checkTransactionStatus(refCommand);

      if (result.success) {
        dispatch({ type: actions.SET_SUCCESS, payload: 'Statut récupéré' });
        return result.data;
      } else {
        throw new Error(result.error);
      }
    } catch (error) {
      dispatch({ type: actions.SET_ERROR, payload: error.message });
      return null;
    }
  }, [dispatch, actions]);

  /**
   * Réinitialiser l'état du paiement
   */
  const resetPayment = useCallback(() => {
    setPaymentState({
      processing: false,
      redirectUrl: null,
      transaction: null
    });
    dispatch({ type: actions.RESET_STATE });
  }, [dispatch, actions]);

  return {
    ...paymentState,
    initiatePayment,
    checkPaymentStatus,
    resetPayment
  };
};
```

## Composants PayTech

### PaymentForm.jsx

```jsx
// src/components/PaymentForm.jsx
import React, { useState } from 'react';
import { usePayTech } from '../context/PayTechContext';
import { usePayment } from '../hooks/usePayment';

const PaymentForm = ({ onSuccess, onError }) => {
  const { config, loading, error } = usePayTech();
  const { initiatePayment, processing } = usePayment();
  
  const [formData, setFormData] = useState({
    itemName: '',
    itemPrice: '',
    paymentMethod: '',
    userPhone: '',
    userFirstName: '',
    userLastName: '',
    autoSubmit: true,
    mobileIntegration: false
  });

  const handleInputChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Validation
    if (!formData.itemName || !formData.itemPrice) {
      onError?.('Veuillez remplir tous les champs obligatoires');
      return;
    }

    if (parseInt(formData.itemPrice) < 100) {
      onError?.('Le montant minimum est de 100 FCFA');
      return;
    }

    // Préparation des données de paiement
    const paymentData = {
      itemName: formData.itemName,
      itemPrice: parseInt(formData.itemPrice),
      currency: 'XOF',
      paymentMethod: formData.paymentMethod || null,
      mobileIntegration: formData.mobileIntegration,
      customField: {
        source: 'react_app',
        userAgent: navigator.userAgent,
        timestamp: new Date().toISOString()
      }
    };

    // Ajout des informations utilisateur pour préfillage
    if (formData.userPhone || formData.userFirstName || formData.userLastName) {
      paymentData.user = {
        phoneNumber: formData.userPhone,
        firstName: formData.userFirstName,
        lastName: formData.userLastName,
        autoSubmit: formData.autoSubmit
      };
    }

    // Initiation du paiement
    const result = await initiatePayment(paymentData);
    
    if (result.success) {
      onSuccess?.(result);
    } else {
      onError?.(result.error);
    }
  };

  return (
    <div className="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
      <h2 className="text-2xl font-bold text-center mb-6">Paiement PayTech</h2>
      
      {error && (
        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          {error}
        </div>
      )}

      <form onSubmit={handleSubmit} className="space-y-4">
        {/* Informations du produit */}
        <div>
          <label htmlFor="itemName" className="block text-sm font-medium text-gray-700">
            Produit/Service *
          </label>
          <input
            type="text"
            id="itemName"
            name="itemName"
            value={formData.itemName}
            onChange={handleInputChange}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="iPhone 13 Pro"
            required
          />
        </div>

        <div>
          <label htmlFor="itemPrice" className="block text-sm font-medium text-gray-700">
            Montant (FCFA) *
          </label>
          <input
            type="number"
            id="itemPrice"
            name="itemPrice"
            value={formData.itemPrice}
            onChange={handleInputChange}
            min="100"
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="650000"
            required
          />
        </div>

        <div>
          <label htmlFor="paymentMethod" className="block text-sm font-medium text-gray-700">
            Méthode de paiement (optionnel)
          </label>
          <select
            id="paymentMethod"
            name="paymentMethod"
            value={formData.paymentMethod}
            onChange={handleInputChange}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          >
            <option value="">Choisir sur PayTech</option>
            {config.PAYMENT_METHODS.map(method => (
              <option key={method} value={method}>{method}</option>
            ))}
          </select>
        </div>

        {/* Informations de préfillage */}
        <div className="border-t pt-4">
          <h3 className="text-lg font-medium text-gray-900 mb-3">
            Informations de préfillage (optionnel)
          </h3>
          
          <div className="grid grid-cols-2 gap-4">
            <div>
              <label htmlFor="userFirstName" className="block text-sm font-medium text-gray-700">
                Prénom
              </label>
              <input
                type="text"
                id="userFirstName"
                name="userFirstName"
                value={formData.userFirstName}
                onChange={handleInputChange}
                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="John"
              />
            </div>
            
            <div>
              <label htmlFor="userLastName" className="block text-sm font-medium text-gray-700">
                Nom
              </label>
              <input
                type="text"
                id="userLastName"
                name="userLastName"
                value={formData.userLastName}
                onChange={handleInputChange}
                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="Doe"
              />
            </div>
          </div>
          
          <div className="mt-4">
            <label htmlFor="userPhone" className="block text-sm font-medium text-gray-700">
              Téléphone
            </label>
            <input
              type="tel"
              id="userPhone"
              name="userPhone"
              value={formData.userPhone}
              onChange={handleInputChange}
              className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              placeholder="+221771234567"
            />
          </div>

          <div className="mt-4 space-y-2">
            <label className="flex items-center">
              <input
                type="checkbox"
                name="autoSubmit"
                checked={formData.autoSubmit}
                onChange={handleInputChange}
                className="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span className="ml-2 text-sm text-gray-700">
                Soumission automatique après préfillage
              </span>
            </label>

            <label className="flex items-center">
              <input
                type="checkbox"
                name="mobileIntegration"
                checked={formData.mobileIntegration}
                onChange={handleInputChange}
                className="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span className="ml-2 text-sm text-gray-700">
                Intégration mobile (React Native/Cordova)
              </span>
            </label>
          </div>
        </div>

        <button
          type="submit"
          disabled={loading || processing}
          className="w-full bg-blue-600 text-white py-3 px-4 rounded-md font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {processing ? 'Traitement...' : 'Payer avec PayTech'}
        </button>
      </form>

      <div className="mt-6 text-center text-sm text-gray-600">
        <p>🔒 Paiement 100% sécurisé</p>
        <p>Propulsé par PayTech Sénégal</p>
      </div>
    </div>
  );
};

export default PaymentForm;
```

### TransactionStatus.jsx

```jsx
// src/components/TransactionStatus.jsx
import React, { useState, useEffect } from 'react';
import { usePayment } from '../hooks/usePayment';

const TransactionStatus = ({ refCommand, onStatusChange }) => {
  const { checkPaymentStatus } = usePayment();
  const [status, setStatus] = useState(null);
  const [loading, setLoading] = useState(false);

  const checkStatus = async () => {
    if (!refCommand) return;
    
    setLoading(true);
    const result = await checkPaymentStatus(refCommand);
    
    if (result) {
      setStatus(result);
      onStatusChange?.(result);
    }
    
    setLoading(false);
  };

  useEffect(() => {
    checkStatus();
  }, [refCommand]);

  if (!refCommand) {
    return (
      <div className="text-center text-gray-500">
        Aucune transaction à vérifier
      </div>
    );
  }

  if (loading) {
    return (
      <div className="text-center">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
        <p className="mt-2 text-gray-600">Vérification du statut...</p>
      </div>
    );
  }

  if (!status) {
    return (
      <div className="text-center">
        <p className="text-red-600">Impossible de récupérer le statut</p>
        <button
          onClick={checkStatus}
          className="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Réessayer
        </button>
      </div>
    );
  }

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'completed': return 'text-green-600';
      case 'pending': return 'text-yellow-600';
      case 'failed': return 'text-red-600';
      case 'cancelled': return 'text-gray-600';
      default: return 'text-gray-600';
    }
  };

  const getStatusIcon = (status) => {
    switch (status.toLowerCase()) {
      case 'completed': return '✅';
      case 'pending': return '⏳';
      case 'failed': return '❌';
      case 'cancelled': return '🚫';
      default: return '❓';
    }
  };

  return (
    <div className="bg-white rounded-lg shadow-lg p-6">
      <h3 className="text-lg font-semibold mb-4">Statut de la transaction</h3>
      
      <div className="space-y-3">
        <div className="flex justify-between">
          <span className="text-gray-600">Référence :</span>
          <span className="font-mono text-sm">{status.refCommand}</span>
        </div>
        
        <div className="flex justify-between">
          <span className="text-gray-600">Statut :</span>
          <span className={`font-semibold ${getStatusColor(status.status)}`}>
            {getStatusIcon(status.status)} {status.status}
          </span>
        </div>
        
        <div className="flex justify-between">
          <span className="text-gray-600">Montant :</span>
          <span className="font-semibold">{status.amount}</span>
        </div>
        
        {status.completedAt && (
          <div className="flex justify-between">
            <span className="text-gray-600">Complété le :</span>
            <span className="text-sm">
              {new Date(status.completedAt).toLocaleString('fr-FR')}
            </span>
          </div>
        )}
        
        <div className="flex justify-between">
          <span className="text-gray-600">Créé le :</span>
          <span className="text-sm">
            {new Date(status.createdAt).toLocaleString('fr-FR')}
          </span>
        </div>
      </div>

      <button
        onClick={checkStatus}
        className="mt-4 w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      >
        Actualiser le statut
      </button>
    </div>
  );
};

export default TransactionStatus;
```

## Pages de l'application

### PaymentPage.jsx

```jsx
// src/pages/PaymentPage.jsx
import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import PaymentForm from '../components/PaymentForm';
import { usePayTech } from '../context/PayTechContext';

const PaymentPage = () => {
  const navigate = useNavigate();
  const { error } = usePayTech();
  const [notification, setNotification] = useState(null);

  const handlePaymentSuccess = (result) => {
    setNotification({
      type: 'success',
      message: 'Redirection vers PayTech en cours...'
    });
    
    // La redirection est gérée automatiquement par le hook usePayment
    // Optionnel : stocker la transaction en localStorage pour le retour
    if (result.transaction) {
      localStorage.setItem('currentTransaction', JSON.stringify(result.transaction));
    }
  };

  const handlePaymentError = (error) => {
    setNotification({
      type: 'error',
      message: error
    });
  };

  return (
    <div className="min-h-screen bg-gray-100 py-8">
      <div className="container mx-auto px-4">
        <div className="text-center mb-8">
          <h1 className="text-3xl font-bold text-gray-900">Paiement PayTech</h1>
          <p className="text-gray-600 mt-2">
            Effectuez vos paiements en toute sécurité avec PayTech
          </p>
        </div>

        {notification && (
          <div className={`max-w-md mx-auto mb-6 p-4 rounded-lg ${
            notification.type === 'success' 
              ? 'bg-green-100 border border-green-400 text-green-700'
              : 'bg-red-100 border border-red-400 text-red-700'
          }`}>
            {notification.message}
          </div>
        )}

        <PaymentForm
          onSuccess={handlePaymentSuccess}
          onError={handlePaymentError}
        />

        <div className="max-w-md mx-auto mt-8 text-center">
          <button
            onClick={() => navigate('/')}
            className="text-blue-600 hover:text-blue-800 underline"
          >
            ← Retour à l'accueil
          </button>
        </div>
      </div>
    </div>
  );
};

export default PaymentPage;
```

### PaymentSuccessPage.jsx

```jsx
// src/pages/PaymentSuccessPage.jsx
import React, { useEffect, useState } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import TransactionStatus from '../components/TransactionStatus';

const PaymentSuccessPage = () => {
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();
  const [transaction, setTransaction] = useState(null);
  
  const refCommand = searchParams.get('ref_command');

  useEffect(() => {
    // Récupérer la transaction depuis localStorage si disponible
    const storedTransaction = localStorage.getItem('currentTransaction');
    if (storedTransaction) {
      try {
        setTransaction(JSON.parse(storedTransaction));
      } catch (error) {
        console.error('Error parsing stored transaction:', error);
      }
    }
  }, []);

  const handleStatusChange = (status) => {
    // Mettre à jour les informations de transaction
    if (status.status === 'COMPLETED') {
      // Nettoyer le localStorage
      localStorage.removeItem('currentTransaction');
    }
  };

  return (
    <div className="min-h-screen bg-gray-100 flex items-center justify-center py-8">
      <div className="max-w-md w-full space-y-6">
        {/* Message de succès */}
        <div className="bg-white p-8 rounded-lg shadow-lg text-center">
          <div className="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
            <svg className="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          
          <h1 className="text-2xl font-bold text-gray-900 mb-4">
            Paiement en cours de traitement
          </h1>
          
          <p className="text-gray-600 mb-6">
            Votre paiement a été initié avec succès. 
            Veuillez patienter pendant que nous vérifions le statut.
          </p>

          {transaction && (
            <div className="bg-gray-50 p-4 rounded-lg mb-6">
              <p className="text-sm text-gray-600">Référence de commande</p>
              <p className="font-mono text-lg">{transaction.refCommand}</p>
            </div>
          )}
        </div>

        {/* Statut de la transaction */}
        {refCommand && (
          <TransactionStatus
            refCommand={refCommand}
            onStatusChange={handleStatusChange}
          />
        )}

        {/* Actions */}
        <div className="space-y-3">
          <button
            onClick={() => navigate('/payment')}
            className="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700"
          >
            Nouveau paiement
          </button>
          <button
            onClick={() => navigate('/')}
            className="w-full bg-gray-200 text-gray-800 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300"
          >
            Retour à l'accueil
          </button>
        </div>
      </div>
    </div>
  );
};

export default PaymentSuccessPage;
```

## Configuration du routeur

### App.jsx

```jsx
// src/App.jsx
import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { PayTechProvider } from './context/PayTechContext';
import PaymentPage from './pages/PaymentPage';
import PaymentSuccessPage from './pages/PaymentSuccessPage';
import PaymentCancelPage from './pages/PaymentCancelPage';
import HomePage from './pages/HomePage';

function App() {
  return (
    <PayTechProvider>
      <Router>
        <div className="App">
          <Routes>
            <Route path="/" element={<HomePage />} />
            <Route path="/payment" element={<PaymentPage />} />
            <Route path="/payment/success" element={<PaymentSuccessPage />} />
            <Route path="/payment/cancel" element={<PaymentCancelPage />} />
          </Routes>
        </div>
      </Router>
    </PayTechProvider>
  );
}

export default App;
```

## Variables d'environnement

### .env

```env
# Configuration de l'API backend
REACT_APP_API_URL=http://localhost:3001/api

# Configuration PayTech (optionnel, géré par le backend)
REACT_APP_PAYTECH_ENV=test

# URLs de retour (optionnel)
REACT_APP_SUCCESS_URL=http://localhost:3000/payment/success
REACT_APP_CANCEL_URL=http://localhost:3000/payment/cancel
```

## Intégration mobile (React Native)

### Configuration spécifique mobile

```javascript
// src/config/mobile.js
import { Platform } from 'react-native';

export const MOBILE_CONFIG = {
  // Détection de l'environnement mobile
  isMobile: Platform.OS === 'ios' || Platform.OS === 'android',
  
  // URLs de retour spécifiques mobile
  RETURN_URLS: {
    success: 'https://paytech.sn/mobile/success',
    cancel: 'https://paytech.sn/mobile/cancel'
  },
  
  // Configuration WebView
  WEBVIEW_CONFIG: {
    javaScriptEnabled: true,
    domStorageEnabled: true,
    startInLoadingState: true,
    scalesPageToFit: true
  }
};
```

### Composant WebView pour React Native

```jsx
// src/components/PayTechWebView.jsx (React Native)
import React, { useState } from 'react';
import { WebView } from 'react-native-webview';
import { View, Alert } from 'react-native';
import { MOBILE_CONFIG } from '../config/mobile';

const PayTechWebView = ({ redirectUrl, onPaymentComplete, onPaymentCancel }) => {
  const [loading, setLoading] = useState(true);

  const handleNavigationStateChange = (navState) => {
    const { url } = navState;
    
    // Vérifier si l'URL correspond au succès
    if (url.includes('paytech.sn/mobile/success')) {
      onPaymentComplete?.(url);
      return false; // Empêcher la navigation
    }
    
    // Vérifier si l'URL correspond à l'annulation
    if (url.includes('paytech.sn/mobile/cancel')) {
      onPaymentCancel?.(url);
      return false; // Empêcher la navigation
    }
    
    return true; // Permettre la navigation
  };

  const handleError = (error) => {
    Alert.alert(
      'Erreur de paiement',
      'Une erreur est survenue lors du chargement de la page de paiement.',
      [{ text: 'OK', onPress: () => onPaymentCancel?.() }]
    );
  };

  return (
    <View style={{ flex: 1 }}>
      <WebView
        source={{ uri: redirectUrl }}
        onNavigationStateChange={handleNavigationStateChange}
        onError={handleError}
        onLoadStart={() => setLoading(true)}
        onLoadEnd={() => setLoading(false)}
        {...MOBILE_CONFIG.WEBVIEW_CONFIG}
      />
    </View>
  );
};

export default PayTechWebView;
```

## Tests

### PaymentForm.test.jsx

```jsx
// src/components/__tests__/PaymentForm.test.jsx
import React from 'react';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { PayTechProvider } from '../../context/PayTechContext';
import PaymentForm from '../PaymentForm';

// Mock du service
jest.mock('../../services/payTechService', () => ({
  payTechService: {
    requestPayment: jest.fn()
  }
}));

const renderWithProvider = (component) => {
  return render(
    <PayTechProvider>
      {component}
    </PayTechProvider>
  );
};

describe('PaymentForm', () => {
  test('renders payment form correctly', () => {
    renderWithProvider(<PaymentForm />);
    
    expect(screen.getByText('Paiement PayTech')).toBeInTheDocument();
    expect(screen.getByLabelText(/Produit\/Service/)).toBeInTheDocument();
    expect(screen.getByLabelText(/Montant/)).toBeInTheDocument();
  });

  test('validates required fields', async () => {
    const onError = jest.fn();
    renderWithProvider(<PaymentForm onError={onError} />);
    
    const submitButton = screen.getByText('Payer avec PayTech');
    fireEvent.click(submitButton);
    
    await waitFor(() => {
      expect(onError).toHaveBeenCalledWith(
        expect.stringContaining('champs obligatoires')
      );
    });
  });

  test('validates minimum amount', async () => {
    const onError = jest.fn();
    renderWithProvider(<PaymentForm onError={onError} />);
    
    fireEvent.change(screen.getByLabelText(/Produit\/Service/), {
      target: { value: 'Test Product' }
    });
    fireEvent.change(screen.getByLabelText(/Montant/), {
      target: { value: '50' }
    });
    
    const submitButton = screen.getByText('Payer avec PayTech');
    fireEvent.click(submitButton);
    
    await waitFor(() => {
      expect(onError).toHaveBeenCalledWith(
        expect.stringContaining('minimum')
      );
    });
  });
});
```

## Bonnes pratiques React

### Gestion d'état

1. **Context API** pour l'état global PayTech
2. **Hooks personnalisés** pour la logique réutilisable
3. **State local** pour les formulaires
4. **Reducers** pour les états complexes

### Performance

1. **Lazy loading** des composants
2. **Memoization** avec React.memo
3. **Code splitting** par route
4. **Optimisation des re-renders**

### Sécurité

1. **Validation côté client** ET serveur
2. **Sanitisation** des entrées utilisateur
3. **HTTPS** obligatoire en production
4. **Variables d'environnement** pour les configurations

### UX/UI

1. **Loading states** pendant les requêtes
2. **Error boundaries** pour les erreurs
3. **Responsive design** mobile-first
4. **Accessibilité** avec ARIA labels

Cette intégration React PayTech offre une solution moderne et complète avec des composants réutilisables, une gestion d'état robuste, et une expérience utilisateur optimale pour les paiements web et mobile.


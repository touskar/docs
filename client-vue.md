# Vue.js - Intégration PayTech

Cette section détaille l'intégration de PayTech dans une application Vue.js, incluant les composants, composables, et la gestion des paiements côté client.

## Vue d'ensemble

Vue.js offre un écosystème moderne pour intégrer PayTech avec :

- **Composition API** pour la logique réutilisable
- **Composables** pour la gestion d'état
- **Pinia** pour le store global
- **Vue Router** pour la navigation
- **Axios** pour les appels API

## Installation et configuration

### Prérequis

- **Node.js** : Version 16 ou supérieure
- **Vue.js** : Version 3+ avec Composition API
- **TypeScript** : Optionnel mais recommandé

### Installation des dépendances

```bash
npm install axios vue-router@4 pinia
# ou avec yarn
yarn add axios vue-router@4 pinia

# Pour TypeScript (optionnel)
npm install -D typescript @vue/typescript
```

### Configuration de base

Créez un fichier de configuration `src/config/paytech.ts` :

```typescript
// src/config/paytech.ts
export interface PayTechConfig {
  API_BASE_URL: string;
  PAYMENT_METHODS: string[];
  RETURN_URLS: {
    success: string;
    cancel: string;
    error: string;
  };
  MOBILE_URLS: {
    success: string;
    cancel: string;
  };
}

export const PAYTECH_CONFIG: PayTechConfig = {
  // URLs de votre backend
  API_BASE_URL: import.meta.env.VITE_API_URL || 'http://localhost:3001/api',
  
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

## Store Pinia

### payTechStore.ts

```typescript
// src/stores/payTechStore.ts
import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { PaymentRequest, PaymentResponse, Transaction } from '@/types/paytech';

export const usePayTechStore = defineStore('paytech', () => {
  // État
  const loading = ref(false);
  const error = ref<string | null>(null);
  const success = ref<string | null>(null);
  const currentTransaction = ref<Transaction | null>(null);
  const transactions = ref<Transaction[]>([]);

  // Getters
  const isLoading = computed(() => loading.value);
  const hasError = computed(() => !!error.value);
  const hasSuccess = computed(() => !!success.value);

  // Actions
  const setLoading = (value: boolean) => {
    loading.value = value;
    if (value) {
      error.value = null;
    }
  };

  const setError = (message: string) => {
    error.value = message;
    loading.value = false;
    success.value = null;
  };

  const setSuccess = (message: string) => {
    success.value = message;
    loading.value = false;
    error.value = null;
  };

  const setCurrentTransaction = (transaction: Transaction) => {
    currentTransaction.value = transaction;
  };

  const addTransaction = (transaction: Transaction) => {
    transactions.value.unshift(transaction);
  };

  const updateTransaction = (refCommand: string, updates: Partial<Transaction>) => {
    const index = transactions.value.findIndex(t => t.refCommand === refCommand);
    if (index !== -1) {
      transactions.value[index] = { ...transactions.value[index], ...updates };
    }
    
    if (currentTransaction.value?.refCommand === refCommand) {
      currentTransaction.value = { ...currentTransaction.value, ...updates };
    }
  };

  const resetState = () => {
    loading.value = false;
    error.value = null;
    success.value = null;
    currentTransaction.value = null;
  };

  const clearError = () => {
    error.value = null;
  };

  const clearSuccess = () => {
    success.value = null;
  };

  return {
    // État
    loading: readonly(loading),
    error: readonly(error),
    success: readonly(success),
    currentTransaction: readonly(currentTransaction),
    transactions: readonly(transactions),
    
    // Getters
    isLoading,
    hasError,
    hasSuccess,
    
    // Actions
    setLoading,
    setError,
    setSuccess,
    setCurrentTransaction,
    addTransaction,
    updateTransaction,
    resetState,
    clearError,
    clearSuccess
  };
});
```

## Service API PayTech

### payTechService.ts

```typescript
// src/services/payTechService.ts
import axios, { type AxiosResponse } from 'axios';
import { PAYTECH_CONFIG } from '@/config/paytech';
import type { 
  PaymentRequest, 
  PaymentResponse, 
  TransactionStatus, 
  ApiResponse 
} from '@/types/paytech';

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
  (response: AxiosResponse) => response,
  (error) => {
    console.error('PayTech API Error:', error);
    return Promise.reject(error);
  }
);

export class PayTechService {
  /**
   * Créer une demande de paiement
   */
  static async requestPayment(paymentData: PaymentRequest): Promise<ApiResponse<PaymentResponse>> {
    try {
      const response = await api.post<PaymentResponse>('/payment/request', paymentData);
      return {
        success: true,
        data: response.data
      };
    } catch (error: any) {
      return {
        success: false,
        error: error.response?.data?.message || error.message || 'Erreur de paiement'
      };
    }
  }

  /**
   * Vérifier le statut d'une transaction
   */
  static async checkTransactionStatus(refCommand: string): Promise<ApiResponse<TransactionStatus>> {
    try {
      const response = await api.get<TransactionStatus>(`/payment/status/${refCommand}`);
      return {
        success: true,
        data: response.data
      };
    } catch (error: any) {
      return {
        success: false,
        error: error.response?.data?.message || error.message || 'Erreur de vérification'
      };
    }
  }

  /**
   * Obtenir l'historique des transactions d'un utilisateur
   */
  static async getUserTransactions(userId: string): Promise<ApiResponse<TransactionStatus[]>> {
    try {
      const response = await api.get<TransactionStatus[]>(`/payment/history/${userId}`);
      return {
        success: true,
        data: response.data
      };
    } catch (error: any) {
      return {
        success: false,
        error: error.response?.data?.message || error.message || 'Erreur de récupération'
      };
    }
  }
}
```

## Types TypeScript

### types/paytech.ts

```typescript
// src/types/paytech.ts
export interface PaymentRequest {
  itemName: string;
  itemPrice: number;
  refCommand?: string;
  commandName?: string;
  currency?: string;
  paymentMethod?: string;
  userId?: string;
  mobileIntegration?: boolean;
  customField?: Record<string, any>;
  user?: UserInfo;
}

export interface UserInfo {
  phoneNumber?: string;
  firstName?: string;
  lastName?: string;
  autoSubmit?: boolean;
}

export interface PaymentResponse {
  success: boolean;
  data: {
    token: string;
    redirect_url: string;
  };
  transaction: Transaction;
}

export interface Transaction {
  id: string;
  refCommand: string;
  itemName: string;
  itemPrice: number;
  currency: string;
  paymentMethod?: string;
  status: TransactionStatus;
  paytechToken?: string;
  redirectUrl?: string;
  userId?: string;
  completedAt?: string;
  createdAt: string;
  updatedAt: string;
}

export type TransactionStatus = 'PENDING' | 'COMPLETED' | 'FAILED' | 'CANCELLED';

export interface TransactionStatusResponse {
  refCommand: string;
  status: TransactionStatus;
  amount: string;
  completedAt?: string;
  createdAt: string;
}

export interface ApiResponse<T> {
  success: boolean;
  data?: T;
  error?: string;
}
```

## Composables

### usePayment.ts

```typescript
// src/composables/usePayment.ts
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { usePayTechStore } from '@/stores/payTechStore';
import { PayTechService } from '@/services/payTechService';
import type { PaymentRequest, Transaction } from '@/types/paytech';

export function usePayment() {
  const router = useRouter();
  const store = usePayTechStore();
  
  const processing = ref(false);
  const redirectUrl = ref<string | null>(null);
  const transaction = ref<Transaction | null>(null);

  // Computed
  const isProcessing = computed(() => processing.value || store.isLoading);

  /**
   * Initier un paiement
   */
  const initiatePayment = async (paymentData: PaymentRequest) => {
    try {
      store.setLoading(true);
      processing.value = true;

      // Génération de la référence de commande si non fournie
      if (!paymentData.refCommand) {
        paymentData.refCommand = `CMD_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
      }

      // Appel au service PayTech
      const result = await PayTechService.requestPayment(paymentData);

      if (result.success && result.data) {
        const transactionData = result.data.transaction;
        const url = result.data.data.redirect_url;

        transaction.value = transactionData;
        redirectUrl.value = url;

        store.setCurrentTransaction(transactionData);
        store.addTransaction(transactionData);
        store.setSuccess('Redirection vers PayTech...');

        // Redirection automatique vers PayTech
        if (url) {
          window.location.href = url;
        }

        return { 
          success: true, 
          redirectUrl: url, 
          transaction: transactionData 
        };
      } else {
        throw new Error(result.error || 'Erreur de paiement');
      }
    } catch (error: any) {
      store.setError(error.message);
      return { 
        success: false, 
        error: error.message 
      };
    } finally {
      processing.value = false;
    }
  };

  /**
   * Vérifier le statut d'un paiement
   */
  const checkPaymentStatus = async (refCommand: string) => {
    try {
      store.setLoading(true);

      const result = await PayTechService.checkTransactionStatus(refCommand);

      if (result.success && result.data) {
        store.updateTransaction(refCommand, {
          status: result.data.status as any,
          completedAt: result.data.completedAt
        });
        
        store.setSuccess('Statut récupéré');
        return result.data;
      } else {
        throw new Error(result.error || 'Erreur de vérification');
      }
    } catch (error: any) {
      store.setError(error.message);
      return null;
    }
  };

  /**
   * Réinitialiser l'état du paiement
   */
  const resetPayment = () => {
    processing.value = false;
    redirectUrl.value = null;
    transaction.value = null;
    store.resetState();
  };

  /**
   * Naviguer vers une page
   */
  const navigateTo = (path: string) => {
    router.push(path);
  };

  return {
    // État
    processing: computed(() => processing.value),
    redirectUrl: computed(() => redirectUrl.value),
    transaction: computed(() => transaction.value),
    isProcessing,
    
    // Actions
    initiatePayment,
    checkPaymentStatus,
    resetPayment,
    navigateTo
  };
}
```

### useTransactionStatus.ts

```typescript
// src/composables/useTransactionStatus.ts
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePayTechStore } from '@/stores/payTechStore';
import { PayTechService } from '@/services/payTechService';
import type { TransactionStatusResponse } from '@/types/paytech';

export function useTransactionStatus(refCommand: string, autoRefresh = false) {
  const store = usePayTechStore();
  const status = ref<TransactionStatusResponse | null>(null);
  const loading = ref(false);
  const refreshInterval = ref<number | null>(null);

  // Computed
  const isCompleted = computed(() => status.value?.status === 'COMPLETED');
  const isPending = computed(() => status.value?.status === 'PENDING');
  const isFailed = computed(() => status.value?.status === 'FAILED');
  const isCancelled = computed(() => status.value?.status === 'CANCELLED');

  /**
   * Vérifier le statut
   */
  const checkStatus = async () => {
    if (!refCommand) return;
    
    loading.value = true;
    
    try {
      const result = await PayTechService.checkTransactionStatus(refCommand);
      
      if (result.success && result.data) {
        status.value = result.data;
        
        // Mettre à jour le store
        store.updateTransaction(refCommand, {
          status: result.data.status as any,
          completedAt: result.data.completedAt
        });
        
        // Arrêter le refresh automatique si complété
        if (autoRefresh && (isCompleted.value || isFailed.value || isCancelled.value)) {
          stopAutoRefresh();
        }
      }
    } catch (error) {
      console.error('Error checking transaction status:', error);
    } finally {
      loading.value = false;
    }
  };

  /**
   * Démarrer le refresh automatique
   */
  const startAutoRefresh = (intervalMs = 5000) => {
    if (refreshInterval.value) return;
    
    refreshInterval.value = window.setInterval(() => {
      if (!isCompleted.value && !isFailed.value && !isCancelled.value) {
        checkStatus();
      }
    }, intervalMs);
  };

  /**
   * Arrêter le refresh automatique
   */
  const stopAutoRefresh = () => {
    if (refreshInterval.value) {
      clearInterval(refreshInterval.value);
      refreshInterval.value = null;
    }
  };

  // Lifecycle
  onMounted(() => {
    checkStatus();
    if (autoRefresh) {
      startAutoRefresh();
    }
  });

  onUnmounted(() => {
    stopAutoRefresh();
  });

  return {
    // État
    status: computed(() => status.value),
    loading: computed(() => loading.value),
    
    // Computed
    isCompleted,
    isPending,
    isFailed,
    isCancelled,
    
    // Actions
    checkStatus,
    startAutoRefresh,
    stopAutoRefresh
  };
}
```

## Composants Vue

### PaymentForm.vue

```vue
<!-- src/components/PaymentForm.vue -->
<template>
  <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold text-center mb-6">Paiement PayTech</h2>
    
    <!-- Messages d'erreur/succès -->
    <div v-if="store.hasError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      {{ store.error }}
    </div>
    
    <div v-if="store.hasSuccess" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      {{ store.success }}
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-4">
      <!-- Informations du produit -->
      <div>
        <label for="itemName" class="block text-sm font-medium text-gray-700">
          Produit/Service *
        </label>
        <input
          id="itemName"
          v-model="form.itemName"
          type="text"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          placeholder="iPhone 13 Pro"
          required
        />
      </div>

      <div>
        <label for="itemPrice" class="block text-sm font-medium text-gray-700">
          Montant (FCFA) *
        </label>
        <input
          id="itemPrice"
          v-model.number="form.itemPrice"
          type="number"
          min="100"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          placeholder="650000"
          required
        />
      </div>

      <div>
        <label for="paymentMethod" class="block text-sm font-medium text-gray-700">
          Méthode de paiement (optionnel)
        </label>
        <select
          id="paymentMethod"
          v-model="form.paymentMethod"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
        >
          <option value="">Choisir sur PayTech</option>
          <option v-for="method in PAYTECH_CONFIG.PAYMENT_METHODS" :key="method" :value="method">
            {{ method }}
          </option>
        </select>
      </div>

      <!-- Informations de préfillage -->
      <div class="border-t pt-4">
        <h3 class="text-lg font-medium text-gray-900 mb-3">
          Informations de préfillage (optionnel)
        </h3>
        
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="userFirstName" class="block text-sm font-medium text-gray-700">
              Prénom
            </label>
            <input
              id="userFirstName"
              v-model="form.userFirstName"
              type="text"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              placeholder="John"
            />
          </div>
          
          <div>
            <label for="userLastName" class="block text-sm font-medium text-gray-700">
              Nom
            </label>
            <input
              id="userLastName"
              v-model="form.userLastName"
              type="text"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              placeholder="Doe"
            />
          </div>
        </div>
        
        <div class="mt-4">
          <label for="userPhone" class="block text-sm font-medium text-gray-700">
            Téléphone
          </label>
          <input
            id="userPhone"
            v-model="form.userPhone"
            type="tel"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="+221771234567"
          />
        </div>

        <div class="mt-4 space-y-2">
          <label class="flex items-center">
            <input
              v-model="form.autoSubmit"
              type="checkbox"
              class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
            <span class="ml-2 text-sm text-gray-700">
              Soumission automatique après préfillage
            </span>
          </label>

          <label class="flex items-center">
            <input
              v-model="form.mobileIntegration"
              type="checkbox"
              class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
            <span class="ml-2 text-sm text-gray-700">
              Intégration mobile (Vue Native/Cordova)
            </span>
          </label>
        </div>
      </div>

      <button
        type="submit"
        :disabled="isProcessing"
        class="w-full bg-blue-600 text-white py-3 px-4 rounded-md font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        {{ isProcessing ? 'Traitement...' : 'Payer avec PayTech' }}
      </button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600">
      <p>🔒 Paiement 100% sécurisé</p>
      <p>Propulsé par PayTech Sénégal</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue';
import { usePayTechStore } from '@/stores/payTechStore';
import { usePayment } from '@/composables/usePayment';
import { PAYTECH_CONFIG } from '@/config/paytech';
import type { PaymentRequest } from '@/types/paytech';

// Props
interface Props {
  onSuccess?: (result: any) => void;
  onError?: (error: string) => void;
}

const props = withDefaults(defineProps<Props>(), {
  onSuccess: () => {},
  onError: () => {}
});

// Composables
const store = usePayTechStore();
const { initiatePayment, isProcessing } = usePayment();

// État du formulaire
const form = reactive({
  itemName: '',
  itemPrice: null as number | null,
  paymentMethod: '',
  userPhone: '',
  userFirstName: '',
  userLastName: '',
  autoSubmit: true,
  mobileIntegration: false
});

// Méthodes
const handleSubmit = async () => {
  // Validation
  if (!form.itemName || !form.itemPrice) {
    props.onError('Veuillez remplir tous les champs obligatoires');
    return;
  }

  if (form.itemPrice < 100) {
    props.onError('Le montant minimum est de 100 FCFA');
    return;
  }

  // Préparation des données de paiement
  const paymentData: PaymentRequest = {
    itemName: form.itemName,
    itemPrice: form.itemPrice,
    currency: 'XOF',
    paymentMethod: form.paymentMethod || undefined,
    mobileIntegration: form.mobileIntegration,
    customField: {
      source: 'vue_app',
      userAgent: navigator.userAgent,
      timestamp: new Date().toISOString()
    }
  };

  // Ajout des informations utilisateur pour préfillage
  if (form.userPhone || form.userFirstName || form.userLastName) {
    paymentData.user = {
      phoneNumber: form.userPhone || undefined,
      firstName: form.userFirstName || undefined,
      lastName: form.userLastName || undefined,
      autoSubmit: form.autoSubmit
    };
  }

  // Initiation du paiement
  const result = await initiatePayment(paymentData);
  
  if (result.success) {
    props.onSuccess(result);
  } else {
    props.onError(result.error || 'Erreur de paiement');
  }
};
</script>
```

### TransactionStatus.vue

```vue
<!-- src/components/TransactionStatus.vue -->
<template>
  <div class="bg-white rounded-lg shadow-lg p-6">
    <h3 class="text-lg font-semibold mb-4">Statut de la transaction</h3>
    
    <div v-if="loading" class="text-center">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
      <p class="mt-2 text-gray-600">Vérification du statut...</p>
    </div>
    
    <div v-else-if="!status" class="text-center">
      <p class="text-red-600">Impossible de récupérer le statut</p>
      <button
        @click="checkStatus"
        class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      >
        Réessayer
      </button>
    </div>
    
    <div v-else class="space-y-3">
      <div class="flex justify-between">
        <span class="text-gray-600">Référence :</span>
        <span class="font-mono text-sm">{{ status.refCommand }}</span>
      </div>
      
      <div class="flex justify-between">
        <span class="text-gray-600">Statut :</span>
        <span :class="getStatusClass(status.status)">
          {{ getStatusIcon(status.status) }} {{ status.status }}
        </span>
      </div>
      
      <div class="flex justify-between">
        <span class="text-gray-600">Montant :</span>
        <span class="font-semibold">{{ status.amount }}</span>
      </div>
      
      <div v-if="status.completedAt" class="flex justify-between">
        <span class="text-gray-600">Complété le :</span>
        <span class="text-sm">
          {{ formatDate(status.completedAt) }}
        </span>
      </div>
      
      <div class="flex justify-between">
        <span class="text-gray-600">Créé le :</span>
        <span class="text-sm">
          {{ formatDate(status.createdAt) }}
        </span>
      </div>
    </div>

    <button
      v-if="status"
      @click="checkStatus"
      :disabled="loading"
      class="mt-4 w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
    >
      Actualiser le statut
    </button>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useTransactionStatus } from '@/composables/useTransactionStatus';
import type { TransactionStatus as Status } from '@/types/paytech';

// Props
interface Props {
  refCommand: string;
  autoRefresh?: boolean;
  onStatusChange?: (status: any) => void;
}

const props = withDefaults(defineProps<Props>(), {
  autoRefresh: false,
  onStatusChange: () => {}
});

// Composables
const { status, loading, checkStatus } = useTransactionStatus(
  props.refCommand, 
  props.autoRefresh
);

// Watchers
watch(status, (newStatus) => {
  if (newStatus) {
    props.onStatusChange(newStatus);
  }
}, { immediate: true });

// Méthodes
const getStatusClass = (status: Status) => {
  const classes = {
    'COMPLETED': 'text-green-600 font-semibold',
    'PENDING': 'text-yellow-600 font-semibold',
    'FAILED': 'text-red-600 font-semibold',
    'CANCELLED': 'text-gray-600 font-semibold'
  };
  return classes[status] || 'text-gray-600 font-semibold';
};

const getStatusIcon = (status: Status) => {
  const icons = {
    'COMPLETED': '✅',
    'PENDING': '⏳',
    'FAILED': '❌',
    'CANCELLED': '🚫'
  };
  return icons[status] || '❓';
};

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleString('fr-FR');
};
</script>
```

## Pages Vue

### PaymentPage.vue

```vue
<!-- src/pages/PaymentPage.vue -->
<template>
  <div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Paiement PayTech</h1>
        <p class="text-gray-600 mt-2">
          Effectuez vos paiements en toute sécurité avec PayTech
        </p>
      </div>

      <Notification
        v-if="notification"
        :type="notification.type"
        :message="notification.message"
        @close="notification = null"
      />

      <PaymentForm
        @success="handlePaymentSuccess"
        @error="handlePaymentError"
      />

      <div class="max-w-md mx-auto mt-8 text-center">
        <button
          @click="$router.push('/')"
          class="text-blue-600 hover:text-blue-800 underline"
        >
          ← Retour à l'accueil
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import PaymentForm from '@/components/PaymentForm.vue';
import Notification from '@/components/Notification.vue';

// Composables
const router = useRouter();

// État
const notification = ref<{type: 'success' | 'error', message: string} | null>(null);

// Méthodes
const handlePaymentSuccess = (result: any) => {
  notification.value = {
    type: 'success',
    message: 'Redirection vers PayTech en cours...'
  };
  
  // Optionnel : stocker la transaction en localStorage pour le retour
  if (result.transaction) {
    localStorage.setItem('currentTransaction', JSON.stringify(result.transaction));
  }
};

const handlePaymentError = (error: string) => {
  notification.value = {
    type: 'error',
    message: error
  };
};
</script>
```

### PaymentSuccessPage.vue

```vue
<!-- src/pages/PaymentSuccessPage.vue -->
<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center py-8">
    <div class="max-w-md w-full space-y-6">
      <!-- Message de succès -->
      <div class="bg-white p-8 rounded-lg shadow-lg text-center">
        <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-900 mb-4">
          Paiement en cours de traitement
        </h1>
        
        <p class="text-gray-600 mb-6">
          Votre paiement a été initié avec succès. 
          Veuillez patienter pendant que nous vérifions le statut.
        </p>

        <div v-if="transaction" class="bg-gray-50 p-4 rounded-lg mb-6">
          <p class="text-sm text-gray-600">Référence de commande</p>
          <p class="font-mono text-lg">{{ transaction.refCommand }}</p>
        </div>
      </div>

      <!-- Statut de la transaction -->
      <TransactionStatus
        v-if="refCommand"
        :ref-command="refCommand"
        :auto-refresh="true"
        @status-change="handleStatusChange"
      />

      <!-- Actions -->
      <div class="space-y-3">
        <button
          @click="$router.push('/payment')"
          class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700"
        >
          Nouveau paiement
        </button>
        <button
          @click="$router.push('/')"
          class="w-full bg-gray-200 text-gray-800 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300"
        >
          Retour à l'accueil
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import TransactionStatus from '@/components/TransactionStatus.vue';
import type { Transaction } from '@/types/paytech';

// Composables
const route = useRoute();

// État
const transaction = ref<Transaction | null>(null);
const refCommand = ref<string>('');

// Lifecycle
onMounted(() => {
  // Récupérer ref_command depuis les query params
  refCommand.value = route.query.ref_command as string || '';
  
  // Récupérer la transaction depuis localStorage si disponible
  const storedTransaction = localStorage.getItem('currentTransaction');
  if (storedTransaction) {
    try {
      transaction.value = JSON.parse(storedTransaction);
    } catch (error) {
      console.error('Error parsing stored transaction:', error);
    }
  }
});

// Méthodes
const handleStatusChange = (status: any) => {
  // Mettre à jour les informations de transaction
  if (status.status === 'COMPLETED') {
    // Nettoyer le localStorage
    localStorage.removeItem('currentTransaction');
  }
};
</script>
```

## Configuration du routeur

### router/index.ts

```typescript
// src/router/index.ts
import { createRouter, createWebHistory } from 'vue-router';
import type { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    name: 'Home',
    component: () => import('@/pages/HomePage.vue')
  },
  {
    path: '/payment',
    name: 'Payment',
    component: () => import('@/pages/PaymentPage.vue')
  },
  {
    path: '/payment/success',
    name: 'PaymentSuccess',
    component: () => import('@/pages/PaymentSuccessPage.vue')
  },
  {
    path: '/payment/cancel',
    name: 'PaymentCancel',
    component: () => import('@/pages/PaymentCancelPage.vue')
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
```

## Configuration principale

### main.ts

```typescript
// src/main.ts
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import App from './App.vue';
import './style.css';

const app = createApp(App);

app.use(createPinia());
app.use(router);

app.mount('#app');
```

### App.vue

```vue
<!-- src/App.vue -->
<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script setup lang="ts">
// Configuration globale de l'application
</script>

<style>
/* Styles globaux */
</style>
```

## Variables d'environnement

### .env

```env
# Configuration de l'API backend
VITE_API_URL=http://localhost:3001/api

# Configuration PayTech (optionnel, géré par le backend)
VITE_PAYTECH_ENV=test

# URLs de retour (optionnel)
VITE_SUCCESS_URL=http://localhost:5173/payment/success
VITE_CANCEL_URL=http://localhost:5173/payment/cancel
```

## Configuration Vite

### vite.config.ts

```typescript
// vite.config.ts
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src')
    }
  },
  server: {
    port: 5173,
    host: true
  },
  build: {
    outDir: 'dist',
    sourcemap: true
  }
});
```

## Tests

### PaymentForm.spec.ts

```typescript
// src/components/__tests__/PaymentForm.spec.ts
import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import { createPinia, setActivePinia } from 'pinia';
import PaymentForm from '../PaymentForm.vue';

// Mock du service
vi.mock('@/services/payTechService', () => ({
  PayTechService: {
    requestPayment: vi.fn()
  }
}));

describe('PaymentForm', () => {
  beforeEach(() => {
    setActivePinia(createPinia());
  });

  it('renders payment form correctly', () => {
    const wrapper = mount(PaymentForm);
    
    expect(wrapper.find('h2').text()).toBe('Paiement PayTech');
    expect(wrapper.find('input[placeholder="iPhone 13 Pro"]').exists()).toBe(true);
    expect(wrapper.find('input[placeholder="650000"]').exists()).toBe(true);
  });

  it('validates required fields', async () => {
    const onError = vi.fn();
    const wrapper = mount(PaymentForm, {
      props: { onError }
    });
    
    await wrapper.find('form').trigger('submit');
    
    expect(onError).toHaveBeenCalledWith(
      expect.stringContaining('champs obligatoires')
    );
  });

  it('validates minimum amount', async () => {
    const onError = vi.fn();
    const wrapper = mount(PaymentForm, {
      props: { onError }
    });
    
    await wrapper.find('input[placeholder="iPhone 13 Pro"]').setValue('Test Product');
    await wrapper.find('input[placeholder="650000"]').setValue('50');
    await wrapper.find('form').trigger('submit');
    
    expect(onError).toHaveBeenCalledWith(
      expect.stringContaining('minimum')
    );
  });
});
```

## Bonnes pratiques Vue.js

### Composition API

1. **Composables** pour la logique réutilisable
2. **Reactive/Ref** pour la réactivité
3. **Computed** pour les valeurs dérivées
4. **Watch** pour les effets de bord

### Gestion d'état

1. **Pinia** pour l'état global
2. **Composables** pour l'état local partagé
3. **Props/Emit** pour la communication parent-enfant
4. **Provide/Inject** pour l'injection de dépendances

### Performance

1. **Lazy loading** des composants
2. **KeepAlive** pour les composants coûteux
3. **V-memo** pour les listes importantes
4. **Suspense** pour le chargement asynchrone

### TypeScript

1. **Types stricts** pour toutes les interfaces
2. **Generics** pour les composables réutilisables
3. **Type guards** pour la validation runtime
4. **Utility types** pour la manipulation de types

Cette intégration Vue.js PayTech offre une solution moderne et complète avec la Composition API, Pinia pour la gestion d'état, des composables réutilisables, et une expérience développeur optimale avec TypeScript.


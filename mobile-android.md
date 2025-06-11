# Android - Intégration PayTech

Cette section détaille l'intégration de PayTech dans une application Android native, incluant les activités, fragments, et la gestion des paiements via WebView.

## Vue d'ensemble

Android offre plusieurs approches pour intégrer PayTech :

- **WebView** pour l'intégration web (recommandé)
- **Custom Tabs** pour une expérience navigateur native
- **Intent** vers le navigateur externe
- **API REST** pour les appels backend

## Prérequis

- **Android Studio** : Version 4.0 ou supérieure
- **SDK Android** : API Level 21 (Android 5.0) minimum
- **Kotlin/Java** : Kotlin recommandé
- **Permissions** : INTERNET, ACCESS_NETWORK_STATE

## Configuration du projet

### build.gradle (Module: app)

```kotlin
// app/build.gradle
android {
    compileSdk 34
    
    defaultConfig {
        applicationId "com.votre.package.paytech"
        minSdk 21
        targetSdk 34
        versionCode 1
        versionName "1.0"
    }
    
    buildFeatures {
        viewBinding true
        dataBinding true
    }
    
    compileOptions {
        sourceCompatibility JavaVersion.VERSION_1_8
        targetCompatibility JavaVersion.VERSION_1_8
    }
    
    kotlinOptions {
        jvmTarget = '1.8'
    }
}

dependencies {
    implementation 'androidx.core:core-ktx:1.12.0'
    implementation 'androidx.appcompat:appcompat:1.6.1'
    implementation 'com.google.android.material:material:1.11.0'
    implementation 'androidx.constraintlayout:constraintlayout:2.1.4'
    implementation 'androidx.lifecycle:lifecycle-viewmodel-ktx:2.7.0'
    implementation 'androidx.lifecycle:lifecycle-livedata-ktx:2.7.0'
    implementation 'androidx.navigation:navigation-fragment-ktx:2.7.6'
    implementation 'androidx.navigation:navigation-ui-ktx:2.7.6'
    
    // Networking
    implementation 'com.squareup.retrofit2:retrofit:2.9.0'
    implementation 'com.squareup.retrofit2:converter-gson:2.9.0'
    implementation 'com.squareup.okhttp3:logging-interceptor:4.12.0'
    
    // WebView
    implementation 'androidx.webkit:webkit:1.9.0'
    
    // Custom Tabs
    implementation 'androidx.browser:browser:1.7.0'
    
    // Coroutines
    implementation 'org.jetbrains.kotlinx:kotlinx-coroutines-android:1.7.3'
    
    // JSON
    implementation 'com.google.code.gson:gson:2.10.1'
}
```

### AndroidManifest.xml

```xml
<!-- app/src/main/AndroidManifest.xml -->
<manifest xmlns:android="http://schemas.android.com/apk/res/android">
    
    <!-- Permissions -->
    <uses-permission android:name="android.permission.INTERNET" />
    <uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
    
    <application
        android:name=".PayTechApplication"
        android:allowBackup="true"
        android:icon="@mipmap/ic_launcher"
        android:label="@string/app_name"
        android:theme="@style/Theme.PayTech"
        android:usesCleartextTraffic="true">
        
        <!-- Activité principale -->
        <activity
            android:name=".ui.MainActivity"
            android:exported="true"
            android:theme="@style/Theme.PayTech.NoActionBar">
            <intent-filter>
                <action android:name="android.intent.action.MAIN" />
                <category android:name="android.intent.category.LAUNCHER" />
            </intent-filter>
        </activity>
        
        <!-- Activité de paiement -->
        <activity
            android:name=".ui.PaymentActivity"
            android:exported="false"
            android:screenOrientation="portrait"
            android:theme="@style/Theme.PayTech.NoActionBar" />
        
        <!-- Activité WebView PayTech -->
        <activity
            android:name=".ui.PayTechWebViewActivity"
            android:exported="false"
            android:screenOrientation="portrait"
            android:theme="@style/Theme.PayTech.NoActionBar" />
        
        <!-- Activité de résultat -->
        <activity
            android:name=".ui.PaymentResultActivity"
            android:exported="true"
            android:launchMode="singleTop">
            <!-- Intent filter pour les retours PayTech -->
            <intent-filter>
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="paytech"
                      android:host="payment" />
            </intent-filter>
        </activity>
    </application>
</manifest>
```

## Configuration PayTech

### PayTechConfig.kt

```kotlin
// app/src/main/java/com/votre/package/config/PayTechConfig.kt
package com.votre.package.config

object PayTechConfig {
    // URLs de votre backend
    const val API_BASE_URL = "https://votre-backend.com/api"
    
    // Configuration PayTech
    val PAYMENT_METHODS = listOf(
        "Orange Money",
        "Tigo Cash",
        "Wave",
        "PayExpresse",
        "Carte Bancaire",
        "Wari",
        "Poste Cash",
        "PayPal",
        "Emoney",
        "Joni Joni"
    )
    
    // URLs de retour mobile
    const val MOBILE_SUCCESS_URL = "paytech://payment/success"
    const val MOBILE_CANCEL_URL = "paytech://payment/cancel"
    const val MOBILE_ERROR_URL = "paytech://payment/error"
    
    // URLs de retour web (fallback)
    const val WEB_SUCCESS_URL = "https://paytech.sn/mobile/success"
    const val WEB_CANCEL_URL = "https://paytech.sn/mobile/cancel"
    
    // Configuration WebView
    const val WEBVIEW_TIMEOUT = 30000L // 30 secondes
    const val WEBVIEW_USER_AGENT = "PayTechAndroid/1.0"
    
    // Préfillage automatique
    const val AUTO_SUBMIT_ENABLED = true
    const val PREFILL_ENABLED = true
}
```

## Modèles de données

### PaymentModels.kt

```kotlin
// app/src/main/java/com/votre/package/model/PaymentModels.kt
package com.votre.package.model

import com.google.gson.annotations.SerializedName

data class PaymentRequest(
    @SerializedName("item_name")
    val itemName: String,
    
    @SerializedName("item_price")
    val itemPrice: Int,
    
    @SerializedName("ref_command")
    val refCommand: String? = null,
    
    @SerializedName("command_name")
    val commandName: String? = null,
    
    @SerializedName("currency")
    val currency: String = "XOF",
    
    @SerializedName("payment_method")
    val paymentMethod: String? = null,
    
    @SerializedName("mobile_integration")
    val mobileIntegration: Boolean = true,
    
    @SerializedName("custom_field")
    val customField: Map<String, Any>? = null,
    
    @SerializedName("user")
    val user: UserInfo? = null
)

data class UserInfo(
    @SerializedName("phone_number")
    val phoneNumber: String? = null,
    
    @SerializedName("first_name")
    val firstName: String? = null,
    
    @SerializedName("last_name")
    val lastName: String? = null,
    
    @SerializedName("auto_submit")
    val autoSubmit: Boolean = true
)

data class PaymentResponse(
    @SerializedName("success")
    val success: Boolean,
    
    @SerializedName("data")
    val data: PayTechData? = null,
    
    @SerializedName("transaction")
    val transaction: Transaction? = null,
    
    @SerializedName("error")
    val error: String? = null
)

data class PayTechData(
    @SerializedName("token")
    val token: String,
    
    @SerializedName("redirect_url")
    val redirectUrl: String
)

data class Transaction(
    @SerializedName("id")
    val id: String,
    
    @SerializedName("ref_command")
    val refCommand: String,
    
    @SerializedName("item_name")
    val itemName: String,
    
    @SerializedName("item_price")
    val itemPrice: Int,
    
    @SerializedName("currency")
    val currency: String,
    
    @SerializedName("payment_method")
    val paymentMethod: String? = null,
    
    @SerializedName("status")
    val status: TransactionStatus,
    
    @SerializedName("paytech_token")
    val paytechToken: String? = null,
    
    @SerializedName("redirect_url")
    val redirectUrl: String? = null,
    
    @SerializedName("user_id")
    val userId: String? = null,
    
    @SerializedName("completed_at")
    val completedAt: String? = null,
    
    @SerializedName("created_at")
    val createdAt: String,
    
    @SerializedName("updated_at")
    val updatedAt: String
)

enum class TransactionStatus {
    @SerializedName("PENDING")
    PENDING,
    
    @SerializedName("COMPLETED")
    COMPLETED,
    
    @SerializedName("FAILED")
    FAILED,
    
    @SerializedName("CANCELLED")
    CANCELLED
}

data class TransactionStatusResponse(
    @SerializedName("ref_command")
    val refCommand: String,
    
    @SerializedName("status")
    val status: TransactionStatus,
    
    @SerializedName("amount")
    val amount: String,
    
    @SerializedName("completed_at")
    val completedAt: String? = null,
    
    @SerializedName("created_at")
    val createdAt: String
)
```

## Service API PayTech

### PayTechApiService.kt

```kotlin
// app/src/main/java/com/votre/package/service/PayTechApiService.kt
package com.votre.package.service

import com.votre.package.model.*
import retrofit2.Response
import retrofit2.http.*

interface PayTechApiService {
    
    @POST("payment/request")
    suspend fun requestPayment(
        @Body request: PaymentRequest
    ): Response<PaymentResponse>
    
    @GET("payment/status/{refCommand}")
    suspend fun getTransactionStatus(
        @Path("refCommand") refCommand: String
    ): Response<TransactionStatusResponse>
    
    @GET("payment/history/{userId}")
    suspend fun getUserTransactions(
        @Path("userId") userId: String
    ): Response<List<TransactionStatusResponse>>
}
```

### PayTechRepository.kt

```kotlin
// app/src/main/java/com/votre/package/repository/PayTechRepository.kt
package com.votre.package.repository

import com.votre.package.config.PayTechConfig
import com.votre.package.model.*
import com.votre.package.service.PayTechApiService
import com.votre.package.utils.NetworkResult
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import java.util.concurrent.TimeUnit

class PayTechRepository {
    
    private val apiService: PayTechApiService
    
    init {
        val loggingInterceptor = HttpLoggingInterceptor().apply {
            level = HttpLoggingInterceptor.Level.BODY
        }
        
        val okHttpClient = OkHttpClient.Builder()
            .addInterceptor(loggingInterceptor)
            .connectTimeout(30, TimeUnit.SECONDS)
            .readTimeout(30, TimeUnit.SECONDS)
            .writeTimeout(30, TimeUnit.SECONDS)
            .build()
        
        val retrofit = Retrofit.Builder()
            .baseUrl(PayTechConfig.API_BASE_URL)
            .client(okHttpClient)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
        
        apiService = retrofit.create(PayTechApiService::class.java)
    }
    
    suspend fun requestPayment(request: PaymentRequest): NetworkResult<PaymentResponse> {
        return withContext(Dispatchers.IO) {
            try {
                val response = apiService.requestPayment(request)
                if (response.isSuccessful) {
                    response.body()?.let { body ->
                        if (body.success) {
                            NetworkResult.Success(body)
                        } else {
                            NetworkResult.Error(body.error ?: "Erreur de paiement")
                        }
                    } ?: NetworkResult.Error("Réponse vide")
                } else {
                    NetworkResult.Error("Erreur HTTP: ${response.code()}")
                }
            } catch (e: Exception) {
                NetworkResult.Error(e.message ?: "Erreur réseau")
            }
        }
    }
    
    suspend fun getTransactionStatus(refCommand: String): NetworkResult<TransactionStatusResponse> {
        return withContext(Dispatchers.IO) {
            try {
                val response = apiService.getTransactionStatus(refCommand)
                if (response.isSuccessful) {
                    response.body()?.let { body ->
                        NetworkResult.Success(body)
                    } ?: NetworkResult.Error("Réponse vide")
                } else {
                    NetworkResult.Error("Erreur HTTP: ${response.code()}")
                }
            } catch (e: Exception) {
                NetworkResult.Error(e.message ?: "Erreur réseau")
            }
        }
    }
    
    suspend fun getUserTransactions(userId: String): NetworkResult<List<TransactionStatusResponse>> {
        return withContext(Dispatchers.IO) {
            try {
                val response = apiService.getUserTransactions(userId)
                if (response.isSuccessful) {
                    response.body()?.let { body ->
                        NetworkResult.Success(body)
                    } ?: NetworkResult.Error("Réponse vide")
                } else {
                    NetworkResult.Error("Erreur HTTP: ${response.code()}")
                }
            } catch (e: Exception) {
                NetworkResult.Error(e.message ?: "Erreur réseau")
            }
        }
    }
}
```

### NetworkResult.kt

```kotlin
// app/src/main/java/com/votre/package/utils/NetworkResult.kt
package com.votre.package.utils

sealed class NetworkResult<T> {
    data class Success<T>(val data: T) : NetworkResult<T>()
    data class Error<T>(val message: String) : NetworkResult<T>()
    data class Loading<T>(val isLoading: Boolean = true) : NetworkResult<T>()
}
```

## ViewModel

### PaymentViewModel.kt

```kotlin
// app/src/main/java/com/votre/package/viewmodel/PaymentViewModel.kt
package com.votre.package.viewmodel

import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.votre.package.config.PayTechConfig
import com.votre.package.model.*
import com.votre.package.repository.PayTechRepository
import com.votre.package.utils.NetworkResult
import kotlinx.coroutines.launch
import java.util.*

class PaymentViewModel : ViewModel() {
    
    private val repository = PayTechRepository()
    
    private val _paymentResult = MutableLiveData<NetworkResult<PaymentResponse>>()
    val paymentResult: LiveData<NetworkResult<PaymentResponse>> = _paymentResult
    
    private val _transactionStatus = MutableLiveData<NetworkResult<TransactionStatusResponse>>()
    val transactionStatus: LiveData<NetworkResult<TransactionStatusResponse>> = _transactionStatus
    
    private val _currentTransaction = MutableLiveData<Transaction?>()
    val currentTransaction: LiveData<Transaction?> = _currentTransaction
    
    private val _isLoading = MutableLiveData<Boolean>()
    val isLoading: LiveData<Boolean> = _isLoading
    
    private val _errorMessage = MutableLiveData<String?>()
    val errorMessage: LiveData<String?> = _errorMessage
    
    fun requestPayment(
        itemName: String,
        itemPrice: Int,
        paymentMethod: String? = null,
        userInfo: UserInfo? = null
    ) {
        viewModelScope.launch {
            _isLoading.value = true
            _paymentResult.value = NetworkResult.Loading()
            
            val request = PaymentRequest(
                itemName = itemName,
                itemPrice = itemPrice,
                refCommand = generateRefCommand(),
                commandName = itemName,
                currency = "XOF",
                paymentMethod = paymentMethod,
                mobileIntegration = true,
                customField = mapOf(
                    "source" to "android_app",
                    "version" to "1.0",
                    "timestamp" to System.currentTimeMillis()
                ),
                user = userInfo
            )
            
            val result = repository.requestPayment(request)
            _paymentResult.value = result
            _isLoading.value = false
            
            when (result) {
                is NetworkResult.Success -> {
                    _currentTransaction.value = result.data.transaction
                    _errorMessage.value = null
                }
                is NetworkResult.Error -> {
                    _errorMessage.value = result.message
                }
                else -> {}
            }
        }
    }
    
    fun checkTransactionStatus(refCommand: String) {
        viewModelScope.launch {
            _isLoading.value = true
            _transactionStatus.value = NetworkResult.Loading()
            
            val result = repository.getTransactionStatus(refCommand)
            _transactionStatus.value = result
            _isLoading.value = false
            
            when (result) {
                is NetworkResult.Error -> {
                    _errorMessage.value = result.message
                }
                else -> {}
            }
        }
    }
    
    fun clearError() {
        _errorMessage.value = null
    }
    
    fun clearResults() {
        _paymentResult.value = null
        _transactionStatus.value = null
        _currentTransaction.value = null
        _errorMessage.value = null
    }
    
    private fun generateRefCommand(): String {
        return "CMD_${System.currentTimeMillis()}_${UUID.randomUUID().toString().take(8)}"
    }
}
```

## Activités

### PaymentActivity.kt

```kotlin
// app/src/main/java/com/votre/package/ui/PaymentActivity.kt
package com.votre.package.ui

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.ArrayAdapter
import android.widget.Toast
import androidx.activity.viewModels
import androidx.appcompat.app.AppCompatActivity
import com.votre.package.R
import com.votre.package.config.PayTechConfig
import com.votre.package.databinding.ActivityPaymentBinding
import com.votre.package.model.UserInfo
import com.votre.package.utils.NetworkResult
import com.votre.package.viewmodel.PaymentViewModel

class PaymentActivity : AppCompatActivity() {
    
    private lateinit var binding: ActivityPaymentBinding
    private val viewModel: PaymentViewModel by viewModels()
    
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityPaymentBinding.inflate(layoutInflater)
        setContentView(binding.root)
        
        setupUI()
        setupObservers()
    }
    
    private fun setupUI() {
        // Configuration de la toolbar
        setSupportActionBar(binding.toolbar)
        supportActionBar?.apply {
            setDisplayHomeAsUpEnabled(true)
            title = "Paiement PayTech"
        }
        
        // Configuration du spinner des méthodes de paiement
        val adapter = ArrayAdapter(
            this,
            android.R.layout.simple_spinner_item,
            listOf("Choisir sur PayTech") + PayTechConfig.PAYMENT_METHODS
        )
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item)
        binding.spinnerPaymentMethod.adapter = adapter
        
        // Configuration des listeners
        binding.buttonPay.setOnClickListener {
            initiatePayment()
        }
        
        binding.checkboxAutoSubmit.isChecked = PayTechConfig.AUTO_SUBMIT_ENABLED
        binding.checkboxMobileIntegration.isChecked = true
    }
    
    private fun setupObservers() {
        viewModel.paymentResult.observe(this) { result ->
            when (result) {
                is NetworkResult.Loading -> {
                    showLoading(true)
                }
                is NetworkResult.Success -> {
                    showLoading(false)
                    result.data.data?.let { data ->
                        openPayTechWebView(data.redirectUrl, result.data.transaction?.refCommand)
                    }
                }
                is NetworkResult.Error -> {
                    showLoading(false)
                    showError(result.message)
                }
                else -> {}
            }
        }
        
        viewModel.errorMessage.observe(this) { error ->
            error?.let {
                showError(it)
                viewModel.clearError()
            }
        }
    }
    
    private fun initiatePayment() {
        // Validation des champs
        val itemName = binding.editTextItemName.text.toString().trim()
        val itemPriceText = binding.editTextItemPrice.text.toString().trim()
        
        if (itemName.isEmpty()) {
            binding.editTextItemName.error = "Le nom du produit est requis"
            return
        }
        
        if (itemPriceText.isEmpty()) {
            binding.editTextItemPrice.error = "Le montant est requis"
            return
        }
        
        val itemPrice = itemPriceText.toIntOrNull()
        if (itemPrice == null || itemPrice < 100) {
            binding.editTextItemPrice.error = "Le montant minimum est de 100 FCFA"
            return
        }
        
        // Récupération de la méthode de paiement
        val paymentMethod = if (binding.spinnerPaymentMethod.selectedItemPosition > 0) {
            PayTechConfig.PAYMENT_METHODS[binding.spinnerPaymentMethod.selectedItemPosition - 1]
        } else {
            null
        }
        
        // Récupération des informations utilisateur pour préfillage
        val userInfo = if (PayTechConfig.PREFILL_ENABLED) {
            val phone = binding.editTextUserPhone.text.toString().trim()
            val firstName = binding.editTextUserFirstName.text.toString().trim()
            val lastName = binding.editTextUserLastName.text.toString().trim()
            
            if (phone.isNotEmpty() || firstName.isNotEmpty() || lastName.isNotEmpty()) {
                UserInfo(
                    phoneNumber = phone.ifEmpty { null },
                    firstName = firstName.ifEmpty { null },
                    lastName = lastName.ifEmpty { null },
                    autoSubmit = binding.checkboxAutoSubmit.isChecked
                )
            } else {
                null
            }
        } else {
            null
        }
        
        // Initiation du paiement
        viewModel.requestPayment(itemName, itemPrice, paymentMethod, userInfo)
    }
    
    private fun openPayTechWebView(redirectUrl: String, refCommand: String?) {
        val intent = Intent(this, PayTechWebViewActivity::class.java).apply {
            putExtra(PayTechWebViewActivity.EXTRA_REDIRECT_URL, redirectUrl)
            putExtra(PayTechWebViewActivity.EXTRA_REF_COMMAND, refCommand)
        }
        startActivity(intent)
    }
    
    private fun showLoading(isLoading: Boolean) {
        binding.progressBar.visibility = if (isLoading) View.VISIBLE else View.GONE
        binding.buttonPay.isEnabled = !isLoading
        binding.buttonPay.text = if (isLoading) "Traitement..." else "Payer avec PayTech"
    }
    
    private fun showError(message: String) {
        Toast.makeText(this, message, Toast.LENGTH_LONG).show()
    }
    
    override fun onSupportNavigateUp(): Boolean {
        onBackPressed()
        return true
    }
}
```

### PayTechWebViewActivity.kt

```kotlin
// app/src/main/java/com/votre/package/ui/PayTechWebViewActivity.kt
package com.votre.package.ui

import android.annotation.SuppressLint
import android.content.Intent
import android.graphics.Bitmap
import android.net.Uri
import android.os.Bundle
import android.view.View
import android.webkit.*
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import com.votre.package.R
import com.votre.package.config.PayTechConfig
import com.votre.package.databinding.ActivityPaytechWebviewBinding

class PayTechWebViewActivity : AppCompatActivity() {
    
    companion object {
        const val EXTRA_REDIRECT_URL = "redirect_url"
        const val EXTRA_REF_COMMAND = "ref_command"
    }
    
    private lateinit var binding: ActivityPaytechWebviewBinding
    private var refCommand: String? = null
    
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityPaytechWebviewBinding.inflate(layoutInflater)
        setContentView(binding.root)
        
        val redirectUrl = intent.getStringExtra(EXTRA_REDIRECT_URL)
        refCommand = intent.getStringExtra(EXTRA_REF_COMMAND)
        
        if (redirectUrl == null) {
            showError("URL de redirection manquante")
            return
        }
        
        setupWebView()
        setupUI()
        loadPayTechUrl(redirectUrl)
    }
    
    private fun setupUI() {
        setSupportActionBar(binding.toolbar)
        supportActionBar?.apply {
            setDisplayHomeAsUpEnabled(true)
            title = "Paiement PayTech"
        }
        
        binding.buttonRetry.setOnClickListener {
            binding.webView.reload()
        }
    }
    
    @SuppressLint("SetJavaScriptEnabled")
    private fun setupWebView() {
        binding.webView.apply {
            settings.apply {
                javaScriptEnabled = true
                domStorageEnabled = true
                loadWithOverviewMode = true
                useWideViewPort = true
                builtInZoomControls = false
                displayZoomControls = false
                setSupportZoom(false)
                userAgentString = PayTechConfig.WEBVIEW_USER_AGENT
                cacheMode = WebSettings.LOAD_NO_CACHE
            }
            
            webViewClient = PayTechWebViewClient()
            webChromeClient = PayTechWebChromeClient()
        }
    }
    
    private fun loadPayTechUrl(url: String) {
        showLoading(true)
        binding.webView.loadUrl(url)
    }
    
    private inner class PayTechWebViewClient : WebViewClient() {
        
        override fun shouldOverrideUrlLoading(view: WebView?, request: WebResourceRequest?): Boolean {
            val url = request?.url?.toString() ?: return false
            
            // Vérifier les URLs de retour mobile
            when {
                url.startsWith(PayTechConfig.MOBILE_SUCCESS_URL) -> {
                    handlePaymentSuccess(url)
                    return true
                }
                url.startsWith(PayTechConfig.MOBILE_CANCEL_URL) -> {
                    handlePaymentCancel(url)
                    return true
                }
                url.startsWith(PayTechConfig.MOBILE_ERROR_URL) -> {
                    handlePaymentError(url)
                    return true
                }
                // Vérifier les URLs de retour web (fallback)
                url.contains("paytech.sn/mobile/success") -> {
                    handlePaymentSuccess(url)
                    return true
                }
                url.contains("paytech.sn/mobile/cancel") -> {
                    handlePaymentCancel(url)
                    return true
                }
                // Permettre la navigation normale pour les autres URLs PayTech
                url.contains("paytech.sn") -> {
                    return false
                }
                // Bloquer les redirections externes
                else -> {
                    return true
                }
            }
        }
        
        override fun onPageStarted(view: WebView?, url: String?, favicon: Bitmap?) {
            super.onPageStarted(view, url, favicon)
            showLoading(true)
        }
        
        override fun onPageFinished(view: WebView?, url: String?) {
            super.onPageFinished(view, url)
            showLoading(false)
            showError(false)
        }
        
        override fun onReceivedError(
            view: WebView?,
            request: WebResourceRequest?,
            error: WebResourceError?
        ) {
            super.onReceivedError(view, request, error)
            showLoading(false)
            showError(true, "Erreur de chargement: ${error?.description}")
        }
        
        override fun onReceivedHttpError(
            view: WebView?,
            request: WebResourceRequest?,
            errorResponse: WebResourceResponse?
        ) {
            super.onReceivedHttpError(view, request, errorResponse)
            if (request?.isForMainFrame == true) {
                showLoading(false)
                showError(true, "Erreur HTTP: ${errorResponse?.statusCode}")
            }
        }
    }
    
    private inner class PayTechWebChromeClient : WebChromeClient() {
        
        override fun onProgressChanged(view: WebView?, newProgress: Int) {
            super.onProgressChanged(view, newProgress)
            binding.progressBar.progress = newProgress
            
            if (newProgress == 100) {
                binding.progressBar.visibility = View.GONE
            } else {
                binding.progressBar.visibility = View.VISIBLE
            }
        }
        
        override fun onJsAlert(
            view: WebView?,
            url: String?,
            message: String?,
            result: JsResult?
        ): Boolean {
            AlertDialog.Builder(this@PayTechWebViewActivity)
                .setTitle("PayTech")
                .setMessage(message)
                .setPositiveButton("OK") { _, _ -> result?.confirm() }
                .setCancelable(false)
                .show()
            return true
        }
    }
    
    private fun handlePaymentSuccess(url: String) {
        val intent = Intent(this, PaymentResultActivity::class.java).apply {
            putExtra(PaymentResultActivity.EXTRA_RESULT_TYPE, PaymentResultActivity.RESULT_SUCCESS)
            putExtra(PaymentResultActivity.EXTRA_REF_COMMAND, refCommand)
            putExtra(PaymentResultActivity.EXTRA_RETURN_URL, url)
        }
        startActivity(intent)
        finish()
    }
    
    private fun handlePaymentCancel(url: String) {
        val intent = Intent(this, PaymentResultActivity::class.java).apply {
            putExtra(PaymentResultActivity.EXTRA_RESULT_TYPE, PaymentResultActivity.RESULT_CANCEL)
            putExtra(PaymentResultActivity.EXTRA_REF_COMMAND, refCommand)
            putExtra(PaymentResultActivity.EXTRA_RETURN_URL, url)
        }
        startActivity(intent)
        finish()
    }
    
    private fun handlePaymentError(url: String) {
        val intent = Intent(this, PaymentResultActivity::class.java).apply {
            putExtra(PaymentResultActivity.EXTRA_RESULT_TYPE, PaymentResultActivity.RESULT_ERROR)
            putExtra(PaymentResultActivity.EXTRA_REF_COMMAND, refCommand)
            putExtra(PaymentResultActivity.EXTRA_RETURN_URL, url)
        }
        startActivity(intent)
        finish()
    }
    
    private fun showLoading(isLoading: Boolean) {
        binding.progressBar.visibility = if (isLoading) View.VISIBLE else View.GONE
    }
    
    private fun showError(hasError: Boolean, message: String? = null) {
        if (hasError) {
            binding.layoutError.visibility = View.VISIBLE
            binding.webView.visibility = View.GONE
            binding.textViewError.text = message ?: "Erreur de chargement"
        } else {
            binding.layoutError.visibility = View.GONE
            binding.webView.visibility = View.VISIBLE
        }
    }
    
    private fun showError(message: String) {
        AlertDialog.Builder(this)
            .setTitle("Erreur")
            .setMessage(message)
            .setPositiveButton("OK") { _, _ -> finish() }
            .setCancelable(false)
            .show()
    }
    
    override fun onSupportNavigateUp(): Boolean {
        onBackPressed()
        return true
    }
    
    override fun onBackPressed() {
        if (binding.webView.canGoBack()) {
            binding.webView.goBack()
        } else {
            super.onBackPressed()
        }
    }
}
```

### PaymentResultActivity.kt

```kotlin
// app/src/main/java/com/votre/package/ui/PaymentResultActivity.kt
package com.votre.package.ui

import android.content.Intent
import android.os.Bundle
import android.view.View
import androidx.activity.viewModels
import androidx.appcompat.app.AppCompatActivity
import com.votre.package.R
import com.votre.package.databinding.ActivityPaymentResultBinding
import com.votre.package.model.TransactionStatus
import com.votre.package.utils.NetworkResult
import com.votre.package.viewmodel.PaymentViewModel

class PaymentResultActivity : AppCompatActivity() {
    
    companion object {
        const val EXTRA_RESULT_TYPE = "result_type"
        const val EXTRA_REF_COMMAND = "ref_command"
        const val EXTRA_RETURN_URL = "return_url"
        
        const val RESULT_SUCCESS = "success"
        const val RESULT_CANCEL = "cancel"
        const val RESULT_ERROR = "error"
    }
    
    private lateinit var binding: ActivityPaymentResultBinding
    private val viewModel: PaymentViewModel by viewModels()
    private var refCommand: String? = null
    
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityPaymentResultBinding.inflate(layoutInflater)
        setContentView(binding.root)
        
        val resultType = intent.getStringExtra(EXTRA_RESULT_TYPE) ?: RESULT_ERROR
        refCommand = intent.getStringExtra(EXTRA_REF_COMMAND)
        val returnUrl = intent.getStringExtra(EXTRA_RETURN_URL)
        
        setupUI(resultType)
        setupObservers()
        
        // Vérifier le statut si on a une référence de commande
        refCommand?.let { ref ->
            viewModel.checkTransactionStatus(ref)
        }
    }
    
    private fun setupUI(resultType: String) {
        setSupportActionBar(binding.toolbar)
        supportActionBar?.apply {
            setDisplayHomeAsUpEnabled(true)
            title = when (resultType) {
                RESULT_SUCCESS -> "Paiement réussi"
                RESULT_CANCEL -> "Paiement annulé"
                else -> "Erreur de paiement"
            }
        }
        
        // Configuration de l'interface selon le type de résultat
        when (resultType) {
            RESULT_SUCCESS -> {
                binding.imageViewResult.setImageResource(R.drawable.ic_success)
                binding.textViewTitle.text = "Paiement en cours de traitement"
                binding.textViewMessage.text = "Votre paiement a été initié avec succès. Veuillez patienter pendant que nous vérifions le statut."
                binding.cardViewResult.setCardBackgroundColor(getColor(R.color.success_light))
            }
            RESULT_CANCEL -> {
                binding.imageViewResult.setImageResource(R.drawable.ic_cancel)
                binding.textViewTitle.text = "Paiement annulé"
                binding.textViewMessage.text = "Votre paiement a été annulé. Aucun montant n'a été débité."
                binding.cardViewResult.setCardBackgroundColor(getColor(R.color.warning_light))
            }
            else -> {
                binding.imageViewResult.setImageResource(R.drawable.ic_error)
                binding.textViewTitle.text = "Erreur de paiement"
                binding.textViewMessage.text = "Une erreur est survenue lors du traitement de votre paiement."
                binding.cardViewResult.setCardBackgroundColor(getColor(R.color.error_light))
            }
        }
        
        // Configuration des boutons
        binding.buttonNewPayment.setOnClickListener {
            startActivity(Intent(this, PaymentActivity::class.java))
            finish()
        }
        
        binding.buttonHome.setOnClickListener {
            startActivity(Intent(this, MainActivity::class.java).apply {
                flags = Intent.FLAG_ACTIVITY_CLEAR_TOP
            })
            finish()
        }
        
        binding.buttonRefreshStatus.setOnClickListener {
            refCommand?.let { ref ->
                viewModel.checkTransactionStatus(ref)
            }
        }
    }
    
    private fun setupObservers() {
        viewModel.transactionStatus.observe(this) { result ->
            when (result) {
                is NetworkResult.Loading -> {
                    showStatusLoading(true)
                }
                is NetworkResult.Success -> {
                    showStatusLoading(false)
                    updateTransactionStatus(result.data)
                }
                is NetworkResult.Error -> {
                    showStatusLoading(false)
                    binding.textViewStatusError.text = result.message
                    binding.textViewStatusError.visibility = View.VISIBLE
                }
                else -> {}
            }
        }
    }
    
    private fun updateTransactionStatus(status: com.votre.package.model.TransactionStatusResponse) {
        binding.layoutTransactionStatus.visibility = View.VISIBLE
        
        binding.textViewRefCommand.text = status.refCommand
        binding.textViewAmount.text = status.amount
        binding.textViewCreatedAt.text = formatDate(status.createdAt)
        
        // Mise à jour du statut
        val (statusText, statusColor) = when (status.status) {
            TransactionStatus.COMPLETED -> {
                "✅ Complété" to getColor(R.color.success)
            }
            TransactionStatus.PENDING -> {
                "⏳ En attente" to getColor(R.color.warning)
            }
            TransactionStatus.FAILED -> {
                "❌ Échoué" to getColor(R.color.error)
            }
            TransactionStatus.CANCELLED -> {
                "🚫 Annulé" to getColor(R.color.gray)
            }
        }
        
        binding.textViewStatus.text = statusText
        binding.textViewStatus.setTextColor(statusColor)
        
        // Afficher la date de completion si disponible
        if (status.completedAt != null) {
            binding.textViewCompletedAt.text = formatDate(status.completedAt)
            binding.layoutCompletedAt.visibility = View.VISIBLE
        } else {
            binding.layoutCompletedAt.visibility = View.GONE
        }
        
        // Masquer le bouton de refresh si le statut est final
        if (status.status in listOf(TransactionStatus.COMPLETED, TransactionStatus.FAILED, TransactionStatus.CANCELLED)) {
            binding.buttonRefreshStatus.visibility = View.GONE
        }
    }
    
    private fun showStatusLoading(isLoading: Boolean) {
        binding.progressBarStatus.visibility = if (isLoading) View.VISIBLE else View.GONE
        binding.buttonRefreshStatus.isEnabled = !isLoading
        binding.textViewStatusError.visibility = View.GONE
    }
    
    private fun formatDate(dateString: String): String {
        return try {
            // Ici vous pouvez utiliser SimpleDateFormat ou une autre bibliothèque
            // pour formater la date selon vos préférences
            dateString
        } catch (e: Exception) {
            dateString
        }
    }
    
    override fun onSupportNavigateUp(): Boolean {
        onBackPressed()
        return true
    }
}
```

## Layouts XML

### activity_payment.xml

```xml
<!-- app/src/main/res/layout/activity_payment.xml -->
<?xml version="1.0" encoding="utf-8"?>
<androidx.coordinatorlayout.widget.CoordinatorLayout 
    xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    android:layout_width="match_parent"
    android:layout_height="match_parent">

    <com.google.android.material.appbar.AppBarLayout
        android:layout_width="match_parent"
        android:layout_height="wrap_content">

        <androidx.appcompat.widget.Toolbar
            android:id="@+id/toolbar"
            android:layout_width="match_parent"
            android:layout_height="?attr/actionBarSize"
            android:background="?attr/colorPrimary"
            android:theme="@style/ThemeOverlay.AppCompat.Dark.ActionBar"
            app:popupTheme="@style/ThemeOverlay.AppCompat.Light" />

    </com.google.android.material.appbar.AppBarLayout>

    <androidx.core.widget.NestedScrollView
        android:layout_width="match_parent"
        android:layout_height="match_parent"
        app:layout_behavior="@string/appbar_scrolling_view_behavior">

        <LinearLayout
            android:layout_width="match_parent"
            android:layout_height="wrap_content"
            android:orientation="vertical"
            android:padding="16dp">

            <com.google.android.material.card.MaterialCardView
                android:layout_width="match_parent"
                android:layout_height="wrap_content"
                android:layout_marginBottom="16dp"
                app:cardCornerRadius="8dp"
                app:cardElevation="4dp">

                <LinearLayout
                    android:layout_width="match_parent"
                    android:layout_height="wrap_content"
                    android:orientation="vertical"
                    android:padding="16dp">

                    <TextView
                        android:layout_width="match_parent"
                        android:layout_height="wrap_content"
                        android:text="Informations du paiement"
                        android:textSize="18sp"
                        android:textStyle="bold"
                        android:layout_marginBottom="16dp" />

                    <com.google.android.material.textfield.TextInputLayout
                        android:layout_width="match_parent"
                        android:layout_height="wrap_content"
                        android:layout_marginBottom="16dp"
                        android:hint="Produit/Service *">

                        <com.google.android.material.textfield.TextInputEditText
                            android:id="@+id/editTextItemName"
                            android:layout_width="match_parent"
                            android:layout_height="wrap_content"
                            android:inputType="text" />

                    </com.google.android.material.textfield.TextInputLayout>

                    <com.google.android.material.textfield.TextInputLayout
                        android:layout_width="match_parent"
                        android:layout_height="wrap_content"
                        android:layout_marginBottom="16dp"
                        android:hint="Montant (FCFA) *">

                        <com.google.android.material.textfield.TextInputEditText
                            android:id="@+id/editTextItemPrice"
                            android:layout_width="match_parent"
                            android:layout_height="wrap_content"
                            android:inputType="number" />

                    </com.google.android.material.textfield.TextInputLayout>

                    <TextView
                        android:layout_width="match_parent"
                        android:layout_height="wrap_content"
                        android:text="Méthode de paiement (optionnel)"
                        android:textSize="14sp"
                        android:layout_marginBottom="8dp" />

                    <Spinner
                        android:id="@+id/spinnerPaymentMethod"
                        android:layout_width="match_parent"
                        android:layout_height="wrap_content"
                        android:layout_marginBottom="16dp" />

                </LinearLayout>

            </com.google.android.material.card.MaterialCardView>

            <com.google.android.material.card.MaterialCardView
                android:layout_width="match_parent"
                android:layout_height="wrap_content"
                android:layout_marginBottom="16dp"
                app:cardCornerRadius="8dp"
                app:cardElevation="4dp">

                <LinearLayout
                    android:layout_width="match_parent"
                    android:layout_height="wrap_content"
                    android:orientation="vertical"
                    android:padding="16dp">

                    <TextView
                        android:layout_width="match_parent"
                        android:layout_height="wrap_content"
                        android:text="Informations de préfillage (optionnel)"
                        android:textSize="18sp"
                        android:textStyle="bold"
                        android:layout_marginBottom="16dp" />

                    <LinearLayout
                        android:layout_width="match_parent"
                        android:layout_height="wrap_content"
                        android:orientation="horizontal"
                        android:layout_marginBottom="16dp">

                        <com.google.android.material.textfield.TextInputLayout
                            android:layout_width="0dp"
                            android:layout_height="wrap_content"
                            android:layout_weight="1"
                            android:layout_marginEnd="8dp"
                            android:hint="Prénom">

                            <com.google.android.material.textfield.TextInputEditText
                                android:id="@+id/editTextUserFirstName"
                                android:layout_width="match_parent"
                                android:layout_height="wrap_content"
                                android:inputType="textPersonName" />

                        </com.google.android.material.textfield.TextInputLayout>

                        <com.google.android.material.textfield.TextInputLayout
                            android:layout_width="0dp"
                            android:layout_height="wrap_content"
                            android:layout_weight="1"
                            android:layout_marginStart="8dp"
                            android:hint="Nom">

                            <com.google.android.material.textfield.TextInputEditText
                                android:id="@+id/editTextUserLastName"
                                android:layout_width="match_parent"
                                android:layout_height="wrap_content"
                                android:inputType="textPersonName" />

                        </com.google.android.material.textfield.TextInputLayout>

                    </LinearLayout>

                    <com.google.android.material.textfield.TextInputLayout
                        android:layout_width="match_parent"
                        android:layout_height="wrap_content"
                        android:layout_marginBottom="16dp"
                        android:hint="Téléphone">

                        <com.google.android.material.textfield.TextInputEditText
                            android:id="@+id/editTextUserPhone"
                            android:layout_width="match_parent"
                            android:layout_height="wrap_content"
                            android:inputType="phone" />

                    </com.google.android.material.textfield.TextInputLayout>

                    <CheckBox
                        android:id="@+id/checkboxAutoSubmit"
                        android:layout_width="match_parent"
                        android:layout_height="wrap_content"
                        android:text="Soumission automatique après préfillage"
                        android:layout_marginBottom="8dp" />

                    <CheckBox
                        android:id="@+id/checkboxMobileIntegration"
                        android:layout_width="match_parent"
                        android:layout_height="wrap_content"
                        android:text="Intégration mobile"
                        android:checked="true" />

                </LinearLayout>

            </com.google.android.material.card.MaterialCardView>

            <ProgressBar
                android:id="@+id/progressBar"
                android:layout_width="wrap_content"
                android:layout_height="wrap_content"
                android:layout_gravity="center"
                android:layout_marginBottom="16dp"
                android:visibility="gone" />

            <com.google.android.material.button.MaterialButton
                android:id="@+id/buttonPay"
                android:layout_width="match_parent"
                android:layout_height="wrap_content"
                android:text="Payer avec PayTech"
                android:textSize="16sp"
                android:padding="16dp"
                app:cornerRadius="8dp" />

            <TextView
                android:layout_width="match_parent"
                android:layout_height="wrap_content"
                android:text="🔒 Paiement 100% sécurisé\nPropulsé par PayTech Sénégal"
                android:textAlignment="center"
                android:textSize="12sp"
                android:layout_marginTop="16dp"
                android:alpha="0.7" />

        </LinearLayout>

    </androidx.core.widget.NestedScrollView>

</androidx.coordinatorlayout.widget.CoordinatorLayout>
```

## Bonnes pratiques Android

### Sécurité

1. **HTTPS obligatoire** pour toutes les communications
2. **Validation des URLs** de retour PayTech
3. **Obfuscation** du code en production
4. **Certificate pinning** pour les API critiques
5. **Stockage sécurisé** des données sensibles

### Performance

1. **Lazy loading** des vues
2. **ViewBinding** pour l'accès aux vues
3. **Coroutines** pour les opérations asynchrones
4. **Caching** des réponses API
5. **Optimisation WebView** avec cache

### UX/UI

1. **Material Design** pour l'interface
2. **Loading states** pendant les requêtes
3. **Error handling** avec messages clairs
4. **Responsive design** pour toutes les tailles
5. **Accessibilité** avec content descriptions

### Architecture

1. **MVVM** avec ViewModel et LiveData
2. **Repository pattern** pour les données
3. **Dependency injection** avec Hilt/Dagger
4. **Navigation component** pour la navigation
5. **Room** pour la persistance locale

Cette intégration Android PayTech offre une solution native complète avec WebView sécurisée, gestion des états, architecture MVVM, et une expérience utilisateur optimale pour les paiements mobiles.


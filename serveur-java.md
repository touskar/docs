# Java - Intégration PayTech

Cette section détaille l'intégration de PayTech dans une application Java, incluant la configuration, l'implémentation des paiements, et la gestion des notifications IPN.

## Vue d'ensemble

Java offre un écosystème robuste pour intégrer PayTech avec :

- **Spring Boot** pour les applications web modernes
- **OkHttp/HttpClient** pour les appels API
- **Jackson** pour la sérialisation JSON
- **JPA/Hibernate** pour la persistance des données
- **Spring Security** pour la sécurité des endpoints

## Prérequis

- **Java** : Version 11 ou supérieure
- **Maven/Gradle** : Pour la gestion des dépendances
- **Spring Boot** : Version 2.7+ ou 3.x
- **Base de données** : MySQL, PostgreSQL, ou H2

## Configuration Maven

Ajoutez les dépendances dans votre `pom.xml` :

```xml
<?xml version="1.0" encoding="UTF-8"?>
<pom>
    <dependencies>
        <!-- Spring Boot Starter Web -->
        <dependency>
            <groupId>org.springframework.boot</groupId>
            <artifactId>spring-boot-starter-web</artifactId>
        </dependency>
        
        <!-- Spring Boot Starter Data JPA -->
        <dependency>
            <groupId>org.springframework.boot</groupId>
            <artifactId>spring-boot-starter-data-jpa</artifactId>
        </dependency>
        
        <!-- Spring Boot Starter Security -->
        <dependency>
            <groupId>org.springframework.boot</groupId>
            <artifactId>spring-boot-starter-security</artifactId>
        </dependency>
        
        <!-- OkHttp pour les appels HTTP -->
        <dependency>
            <groupId>com.squareup.okhttp3</groupId>
            <artifactId>okhttp</artifactId>
            <version>4.12.0</version>
        </dependency>
        
        <!-- Jackson pour JSON -->
        <dependency>
            <groupId>com.fasterxml.jackson.core</groupId>
            <artifactId>jackson-databind</artifactId>
        </dependency>
        
        <!-- Base de données MySQL -->
        <dependency>
            <groupId>mysql</groupId>
            <artifactId>mysql-connector-java</artifactId>
            <scope>runtime</scope>
        </dependency>
        
        <!-- Validation -->
        <dependency>
            <groupId>org.springframework.boot</groupId>
            <artifactId>spring-boot-starter-validation</artifactId>
        </dependency>
    </dependencies>
</pom>
```

## Configuration de l'application

### application.yml

```yaml
# Configuration PayTech
paytech:
  api-key: ${PAYTECH_API_KEY:your_api_key}
  secret-key: ${PAYTECH_SECRET_KEY:your_secret_key}
  environment: ${PAYTECH_ENV:test}
  base-url: https://paytech.sn/api
  timeout: 30
  urls:
    ipn: ${PAYTECH_IPN_URL:https://votre-site.com/webhooks/paytech}
    success: ${PAYTECH_SUCCESS_URL:https://votre-site.com/payment/success}
    cancel: ${PAYTECH_CANCEL_URL:https://votre-site.com/payment/cancel}
    mobile-success: https://paytech.sn/mobile/success
    mobile-cancel: https://paytech.sn/mobile/cancel

# Configuration base de données
spring:
  datasource:
    url: jdbc:mysql://localhost:3306/paytech_db
    username: ${DB_USERNAME:root}
    password: ${DB_PASSWORD:password}
    driver-class-name: com.mysql.cj.jdbc.Driver
  
  jpa:
    hibernate:
      ddl-auto: update
    show-sql: false
    properties:
      hibernate:
        dialect: org.hibernate.dialect.MySQL8Dialect
        format_sql: true

# Configuration serveur
server:
  port: 8080
  servlet:
    context-path: /api

# Logging
logging:
  level:
    com.votre.package.paytech: DEBUG
    org.springframework.security: DEBUG
  pattern:
    console: "%d{yyyy-MM-dd HH:mm:ss} - %msg%n"
```

## Configuration PayTech

### PayTechConfig.java

```java
package com.votre.package.config;

import org.springframework.boot.context.properties.ConfigurationProperties;
import org.springframework.context.annotation.Configuration;

@Configuration
@ConfigurationProperties(prefix = "paytech")
public class PayTechConfig {
    
    private String apiKey;
    private String secretKey;
    private String environment;
    private String baseUrl;
    private int timeout;
    private Urls urls;
    
    // Getters et Setters
    public String getApiKey() { return apiKey; }
    public void setApiKey(String apiKey) { this.apiKey = apiKey; }
    
    public String getSecretKey() { return secretKey; }
    public void setSecretKey(String secretKey) { this.secretKey = secretKey; }
    
    public String getEnvironment() { return environment; }
    public void setEnvironment(String environment) { this.environment = environment; }
    
    public String getBaseUrl() { return baseUrl; }
    public void setBaseUrl(String baseUrl) { this.baseUrl = baseUrl; }
    
    public int getTimeout() { return timeout; }
    public void setTimeout(int timeout) { this.timeout = timeout; }
    
    public Urls getUrls() { return urls; }
    public void setUrls(Urls urls) { this.urls = urls; }
    
    public static class Urls {
        private String ipn;
        private String success;
        private String cancel;
        private String mobileSuccess;
        private String mobileCancel;
        
        // Getters et Setters
        public String getIpn() { return ipn; }
        public void setIpn(String ipn) { this.ipn = ipn; }
        
        public String getSuccess() { return success; }
        public void setSuccess(String success) { this.success = success; }
        
        public String getCancel() { return cancel; }
        public void setCancel(String cancel) { this.cancel = cancel; }
        
        public String getMobileSuccess() { return mobileSuccess; }
        public void setMobileSuccess(String mobileSuccess) { this.mobileSuccess = mobileSuccess; }
        
        public String getMobileCancel() { return mobileCancel; }
        public void setMobileCancel(String mobileCancel) { this.mobileCancel = mobileCancel; }
    }
}
```

## Modèles de données

### PayTechTransaction.java

```java
package com.votre.package.model;

import jakarta.persistence.*;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.Positive;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import java.time.LocalDateTime;
import java.util.Map;

@Entity
@Table(name = "paytech_transactions")
@JsonIgnoreProperties(ignoreUnknown = true)
public class PayTechTransaction {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    
    @Column(unique = true, nullable = false)
    @NotNull
    private String refCommand;
    
    @Column(nullable = false)
    @NotNull
    private String itemName;
    
    @Column(nullable = false)
    @Positive
    private Integer itemPrice;
    
    @Column(length = 3)
    private String currency = "XOF";
    
    private String paymentMethod;
    
    @Enumerated(EnumType.STRING)
    private TransactionStatus status = TransactionStatus.PENDING;
    
    private String paytechToken;
    
    @Column(columnDefinition = "TEXT")
    private String redirectUrl;
    
    @ElementCollection
    @CollectionTable(name = "transaction_custom_fields")
    private Map<String, String> customField;
    
    private Long userId;
    
    private LocalDateTime completedAt;
    
    @Column(columnDefinition = "JSON")
    private String ipnData;
    
    @Column(nullable = false, updatable = false)
    private LocalDateTime createdAt = LocalDateTime.now();
    
    @Column(nullable = false)
    private LocalDateTime updatedAt = LocalDateTime.now();
    
    // Constructeurs
    public PayTechTransaction() {}
    
    public PayTechTransaction(String refCommand, String itemName, Integer itemPrice) {
        this.refCommand = refCommand;
        this.itemName = itemName;
        this.itemPrice = itemPrice;
    }
    
    // Getters et Setters
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    
    public String getRefCommand() { return refCommand; }
    public void setRefCommand(String refCommand) { this.refCommand = refCommand; }
    
    public String getItemName() { return itemName; }
    public void setItemName(String itemName) { this.itemName = itemName; }
    
    public Integer getItemPrice() { return itemPrice; }
    public void setItemPrice(Integer itemPrice) { this.itemPrice = itemPrice; }
    
    public String getCurrency() { return currency; }
    public void setCurrency(String currency) { this.currency = currency; }
    
    public String getPaymentMethod() { return paymentMethod; }
    public void setPaymentMethod(String paymentMethod) { this.paymentMethod = paymentMethod; }
    
    public TransactionStatus getStatus() { return status; }
    public void setStatus(TransactionStatus status) { this.status = status; }
    
    public String getPaytechToken() { return paytechToken; }
    public void setPaytechToken(String paytechToken) { this.paytechToken = paytechToken; }
    
    public String getRedirectUrl() { return redirectUrl; }
    public void setRedirectUrl(String redirectUrl) { this.redirectUrl = redirectUrl; }
    
    public Map<String, String> getCustomField() { return customField; }
    public void setCustomField(Map<String, String> customField) { this.customField = customField; }
    
    public Long getUserId() { return userId; }
    public void setUserId(Long userId) { this.userId = userId; }
    
    public LocalDateTime getCompletedAt() { return completedAt; }
    public void setCompletedAt(LocalDateTime completedAt) { this.completedAt = completedAt; }
    
    public String getIpnData() { return ipnData; }
    public void setIpnData(String ipnData) { this.ipnData = ipnData; }
    
    public LocalDateTime getCreatedAt() { return createdAt; }
    public void setCreatedAt(LocalDateTime createdAt) { this.createdAt = createdAt; }
    
    public LocalDateTime getUpdatedAt() { return updatedAt; }
    public void setUpdatedAt(LocalDateTime updatedAt) { this.updatedAt = updatedAt; }
    
    // Méthodes utilitaires
    public boolean isCompleted() {
        return status == TransactionStatus.COMPLETED;
    }
    
    public String getFormattedAmount() {
        return String.format("%,d %s", itemPrice, currency);
    }
    
    @PreUpdate
    public void preUpdate() {
        updatedAt = LocalDateTime.now();
    }
    
    public enum TransactionStatus {
        PENDING, COMPLETED, FAILED, CANCELLED
    }
}
```

## Service PayTech

### PayTechService.java

```java
package com.votre.package.service;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.votre.package.config.PayTechConfig;
import com.votre.package.dto.*;
import com.votre.package.model.PayTechTransaction;
import com.votre.package.repository.PayTechTransactionRepository;
import okhttp3.*;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;
import java.nio.charset.StandardCharsets;
import java.security.MessageDigest;
import java.time.LocalDateTime;
import java.util.concurrent.TimeUnit;

@Service
public class PayTechService {
    
    private static final Logger logger = LoggerFactory.getLogger(PayTechService.class);
    private static final MediaType JSON = MediaType.get("application/json; charset=utf-8");
    
    @Autowired
    private PayTechConfig payTechConfig;
    
    @Autowired
    private PayTechTransactionRepository transactionRepository;
    
    @Autowired
    private ObjectMapper objectMapper;
    
    private final OkHttpClient httpClient;
    
    public PayTechService() {
        this.httpClient = new OkHttpClient.Builder()
                .connectTimeout(30, TimeUnit.SECONDS)
                .writeTimeout(30, TimeUnit.SECONDS)
                .readTimeout(30, TimeUnit.SECONDS)
                .build();
    }
    
    /**
     * Créer une demande de paiement
     */
    public PaymentResponse requestPayment(PaymentRequest request) {
        try {
            // Validation des données
            validatePaymentRequest(request);
            
            // Préparation de la requête
            PayTechPaymentRequest payTechRequest = preparePaymentRequest(request);
            
            // Appel à l'API PayTech
            String jsonRequest = objectMapper.writeValueAsString(payTechRequest);
            RequestBody body = RequestBody.create(jsonRequest, JSON);
            
            Request httpRequest = new Request.Builder()
                    .url(payTechConfig.getBaseUrl() + "/payment/request-payment")
                    .addHeader("API_KEY", payTechConfig.getApiKey())
                    .addHeader("API_SECRET", payTechConfig.getSecretKey())
                    .post(body)
                    .build();
            
            try (Response response = httpClient.newCall(httpRequest).execute()) {
                if (response.isSuccessful() && response.body() != null) {
                    String responseBody = response.body().string();
                    PayTechApiResponse apiResponse = objectMapper.readValue(responseBody, PayTechApiResponse.class);
                    
                    // Sauvegarde en base de données
                    PayTechTransaction transaction = saveTransaction(request, apiResponse);
                    
                    // Ajout des paramètres de préfillage si utilisateur fourni
                    if (request.getUser() != null) {
                        String enhancedUrl = addUserPrefillParams(
                                apiResponse.getRedirectUrl(),
                                request.getUser(),
                                request.getPaymentMethod()
                        );
                        apiResponse.setRedirectUrl(enhancedUrl);
                    }
                    
                    return PaymentResponse.success(apiResponse, transaction);
                } else {
                    logger.error("PayTech API Error: {} - {}", response.code(), response.message());
                    return PaymentResponse.error("Erreur lors de la communication avec PayTech");
                }
            }
            
        } catch (Exception e) {
            logger.error("PayTech Payment Error", e);
            return PaymentResponse.error(e.getMessage());
        }
    }
    
    /**
     * Validation des données de paiement
     */
    private void validatePaymentRequest(PaymentRequest request) {
        if (request.getItemName() == null || request.getItemName().trim().isEmpty()) {
            throw new IllegalArgumentException("Le nom de l'article est requis");
        }
        if (request.getItemPrice() == null || request.getItemPrice() <= 0) {
            throw new IllegalArgumentException("Le prix doit être supérieur à 0");
        }
        if (request.getRefCommand() == null || request.getRefCommand().trim().isEmpty()) {
            throw new IllegalArgumentException("La référence de commande est requise");
        }
    }
    
    /**
     * Préparation de la requête PayTech
     */
    private PayTechPaymentRequest preparePaymentRequest(PaymentRequest request) {
        boolean isMobile = request.isMobileIntegration();
        
        PayTechPaymentRequest payTechRequest = new PayTechPaymentRequest();
        payTechRequest.setItemName(request.getItemName());
        payTechRequest.setItemPrice(request.getItemPrice());
        payTechRequest.setCurrency(request.getCurrency() != null ? request.getCurrency() : "XOF");
        payTechRequest.setRefCommand(request.getRefCommand());
        payTechRequest.setCommandName(request.getCommandName() != null ? request.getCommandName() : request.getItemName());
        payTechRequest.setEnv(payTechConfig.getEnvironment());
        payTechRequest.setTargetPayment(request.getPaymentMethod());
        
        // URLs de retour
        if (isMobile) {
            payTechRequest.setSuccessUrl(payTechConfig.getUrls().getMobileSuccess());
            payTechRequest.setCancelUrl(payTechConfig.getUrls().getMobileCancel());
        } else {
            payTechRequest.setSuccessUrl(payTechConfig.getUrls().getSuccess());
            payTechRequest.setCancelUrl(payTechConfig.getUrls().getCancel());
        }
        
        payTechRequest.setIpnUrl(payTechConfig.getUrls().getIpn());
        
        // Champ personnalisé
        if (request.getCustomField() != null) {
            try {
                payTechRequest.setCustomField(objectMapper.writeValueAsString(request.getCustomField()));
            } catch (Exception e) {
                logger.warn("Error serializing custom field", e);
                payTechRequest.setCustomField("{}");
            }
        }
        
        return payTechRequest;
    }
    
    /**
     * Ajout des paramètres de préfillage utilisateur
     */
    private String addUserPrefillParams(String redirectUrl, UserInfo user, String paymentMethod) {
        StringBuilder urlBuilder = new StringBuilder(redirectUrl);
        boolean hasParams = redirectUrl.contains("?");
        
        if (user.getPhoneNumber() != null) {
            urlBuilder.append(hasParams ? "&" : "?").append("pn=").append(user.getPhoneNumber());
            hasParams = true;
            
            // Numéro national (sans indicatif)
            if (user.getPhoneNumber().startsWith("+221")) {
                urlBuilder.append("&nn=").append(user.getPhoneNumber().substring(4));
            }
        }
        
        if (user.getFirstName() != null && user.getLastName() != null) {
            String fullName = user.getFirstName() + " " + user.getLastName();
            urlBuilder.append(hasParams ? "&" : "?").append("fn=").append(fullName);
            hasParams = true;
        }
        
        if (paymentMethod != null) {
            urlBuilder.append(hasParams ? "&" : "?").append("tp=").append(paymentMethod);
            hasParams = true;
        }
        
        // Auto-submit
        boolean autoSubmit = user.isAutoSubmit();
        urlBuilder.append(hasParams ? "&" : "?").append("nac=").append(autoSubmit ? "1" : "0");
        
        return urlBuilder.toString();
    }
    
    /**
     * Sauvegarde de la transaction
     */
    private PayTechTransaction saveTransaction(PaymentRequest request, PayTechApiResponse response) {
        PayTechTransaction transaction = new PayTechTransaction();
        transaction.setRefCommand(request.getRefCommand());
        transaction.setItemName(request.getItemName());
        transaction.setItemPrice(request.getItemPrice());
        transaction.setCurrency(request.getCurrency() != null ? request.getCurrency() : "XOF");
        transaction.setPaymentMethod(request.getPaymentMethod());
        transaction.setStatus(PayTechTransaction.TransactionStatus.PENDING);
        transaction.setPaytechToken(response.getToken());
        transaction.setRedirectUrl(response.getRedirectUrl());
        transaction.setUserId(request.getUserId());
        
        if (request.getCustomField() != null) {
            transaction.setCustomField(request.getCustomField());
        }
        
        return transactionRepository.save(transaction);
    }
    
    /**
     * Validation des notifications IPN
     */
    public boolean validateIPN(IPNData data) {
        // Validation HMAC si disponible
        if (data.getHmacCompute() != null && !data.getHmacCompute().isEmpty()) {
            return validateHMACSignature(data);
        }
        
        // Fallback sur validation SHA256
        if (data.getApiKeySha256() != null && data.getApiSecretSha256() != null) {
            return validateSHA256Signature(data);
        }
        
        return false;
    }
    
    /**
     * Validation HMAC
     */
    private boolean validateHMACSignature(IPNData data) {
        try {
            String amount = String.valueOf(data.getItemPrice() != null ? data.getItemPrice() : data.getAmount());
            String idTransaction = data.getToken() != null ? data.getToken() : data.getIdTransaction();
            String receivedHmac = data.getHmacCompute();
            
            if (amount == null || idTransaction == null || receivedHmac == null) {
                return false;
            }
            
            String message = amount + "|" + idTransaction + "|" + payTechConfig.getApiKey();
            String expectedHmac = generateHMAC(message, payTechConfig.getSecretKey());
            
            return MessageDigest.isEqual(expectedHmac.getBytes(), receivedHmac.getBytes());
            
        } catch (Exception e) {
            logger.error("Error validating HMAC signature", e);
            return false;
        }
    }
    
    /**
     * Validation SHA256
     */
    private boolean validateSHA256Signature(IPNData data) {
        try {
            String receivedApiKeyHash = data.getApiKeySha256();
            String receivedSecretHash = data.getApiSecretSha256();
            
            String expectedApiKeyHash = generateSHA256(payTechConfig.getApiKey());
            String expectedSecretHash = generateSHA256(payTechConfig.getSecretKey());
            
            return MessageDigest.isEqual(expectedApiKeyHash.getBytes(), receivedApiKeyHash.getBytes()) &&
                   MessageDigest.isEqual(expectedSecretHash.getBytes(), receivedSecretHash.getBytes());
            
        } catch (Exception e) {
            logger.error("Error validating SHA256 signature", e);
            return false;
        }
    }
    
    /**
     * Traitement des notifications IPN
     */
    public boolean processIPN(IPNData data) {
        try {
            // Validation de l'authenticité
            if (!validateIPN(data)) {
                logger.warn("PayTech IPN: Invalid signature for ref_command: {}", data.getRefCommand());
                return false;
            }
            
            // Recherche de la transaction
            PayTechTransaction transaction = transactionRepository.findByRefCommand(data.getRefCommand());
            if (transaction == null) {
                logger.warn("PayTech IPN: Transaction not found for ref_command: {}", data.getRefCommand());
                return false;
            }
            
            // Vérification du montant
            Integer amount = data.getItemPrice() != null ? data.getItemPrice() : data.getAmount();
            if (!transaction.getItemPrice().equals(amount)) {
                logger.warn("PayTech IPN: Amount mismatch for ref_command: {} - expected: {}, received: {}",
                        data.getRefCommand(), transaction.getItemPrice(), amount);
                return false;
            }
            
            // Mise à jour du statut
            if ("sale_complete".equals(data.getTypeEvent())) {
                transaction.setStatus(PayTechTransaction.TransactionStatus.COMPLETED);
                transaction.setPaymentMethod(data.getPaymentMethod() != null ? data.getPaymentMethod() : transaction.getPaymentMethod());
                transaction.setCompletedAt(LocalDateTime.now());
                
                try {
                    transaction.setIpnData(objectMapper.writeValueAsString(data));
                } catch (Exception e) {
                    logger.warn("Error serializing IPN data", e);
                }
                
                transactionRepository.save(transaction);
                
                // Déclenchement des événements post-paiement
                triggerPostPaymentEvents(transaction);
                
                return true;
            }
            
            return false;
            
        } catch (Exception e) {
            logger.error("PayTech IPN Processing Error for ref_command: " + data.getRefCommand(), e);
            return false;
        }
    }
    
    /**
     * Événements post-paiement
     */
    private void triggerPostPaymentEvents(PayTechTransaction transaction) {
        // Ici vous pouvez déclencher des événements Spring
        // applicationEventPublisher.publishEvent(new PaymentCompletedEvent(transaction));
        
        logger.info("Payment completed for transaction: {} - Amount: {} {}",
                transaction.getRefCommand(),
                transaction.getItemPrice(),
                transaction.getCurrency());
    }
    
    /**
     * Génération HMAC SHA256
     */
    private String generateHMAC(String message, String secretKey) throws Exception {
        Mac mac = Mac.getInstance("HmacSHA256");
        SecretKeySpec secretKeySpec = new SecretKeySpec(secretKey.getBytes(StandardCharsets.UTF_8), "HmacSHA256");
        mac.init(secretKeySpec);
        byte[] hash = mac.doFinal(message.getBytes(StandardCharsets.UTF_8));
        
        StringBuilder hexString = new StringBuilder();
        for (byte b : hash) {
            String hex = Integer.toHexString(0xff & b);
            if (hex.length() == 1) {
                hexString.append('0');
            }
            hexString.append(hex);
        }
        return hexString.toString();
    }
    
    /**
     * Génération SHA256
     */
    private String generateSHA256(String input) throws Exception {
        MessageDigest digest = MessageDigest.getInstance("SHA-256");
        byte[] hash = digest.digest(input.getBytes(StandardCharsets.UTF_8));
        
        StringBuilder hexString = new StringBuilder();
        for (byte b : hash) {
            String hex = Integer.toHexString(0xff & b);
            if (hex.length() == 1) {
                hexString.append('0');
            }
            hexString.append(hex);
        }
        return hexString.toString();
    }
}
```

## Contrôleur REST

### PaymentController.java

```java
package com.votre.package.controller;

import com.votre.package.dto.*;
import com.votre.package.model.PayTechTransaction;
import com.votre.package.service.PayTechService;
import com.votre.package.repository.PayTechTransactionRepository;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import jakarta.validation.Valid;
import java.time.Instant;
import java.util.Map;

@RestController
@RequestMapping("/payment")
@CrossOrigin(origins = "*")
public class PaymentController {
    
    private static final Logger logger = LoggerFactory.getLogger(PaymentController.class);
    
    @Autowired
    private PayTechService payTechService;
    
    @Autowired
    private PayTechTransactionRepository transactionRepository;
    
    /**
     * Créer une demande de paiement
     */
    @PostMapping("/request")
    public ResponseEntity<PaymentResponse> requestPayment(@Valid @RequestBody PaymentRequest request) {
        try {
            // Génération de la référence de commande si non fournie
            if (request.getRefCommand() == null || request.getRefCommand().isEmpty()) {
                request.setRefCommand("CMD_" + Instant.now().getEpochSecond() + "_" + 
                                    Integer.toHexString((int)(Math.random() * 0x10000)));
            }
            
            logger.info("Processing payment request for ref_command: {}", request.getRefCommand());
            
            PaymentResponse response = payTechService.requestPayment(request);
            
            if (response.isSuccess()) {
                logger.info("Payment request successful for ref_command: {}", request.getRefCommand());
                return ResponseEntity.ok(response);
            } else {
                logger.warn("Payment request failed for ref_command: {} - Errors: {}", 
                          request.getRefCommand(), response.getErrors());
                return ResponseEntity.badRequest().body(response);
            }
            
        } catch (Exception e) {
            logger.error("Payment request error", e);
            return ResponseEntity.internalServerError()
                    .body(PaymentResponse.error("Erreur interne du serveur"));
        }
    }
    
    /**
     * Vérifier le statut d'une transaction
     */
    @GetMapping("/status/{refCommand}")
    public ResponseEntity<TransactionStatusResponse> getTransactionStatus(@PathVariable String refCommand) {
        try {
            PayTechTransaction transaction = transactionRepository.findByRefCommand(refCommand);
            
            if (transaction == null) {
                return ResponseEntity.notFound().build();
            }
            
            TransactionStatusResponse response = new TransactionStatusResponse();
            response.setRefCommand(transaction.getRefCommand());
            response.setStatus(transaction.getStatus().name());
            response.setAmount(transaction.getFormattedAmount());
            response.setCompletedAt(transaction.getCompletedAt());
            response.setCreatedAt(transaction.getCreatedAt());
            
            return ResponseEntity.ok(response);
            
        } catch (Exception e) {
            logger.error("Error getting transaction status for ref_command: " + refCommand, e);
            return ResponseEntity.internalServerError().build();
        }
    }
    
    /**
     * Endpoint IPN pour les notifications PayTech
     */
    @PostMapping("/ipn")
    public ResponseEntity<String> handleIPN(@RequestBody Map<String, Object> rawData) {
        try {
            logger.info("PayTech IPN Received: {}", rawData);
            
            // Conversion des données en objet IPNData
            IPNData ipnData = convertToIPNData(rawData);
            
            // Traitement de l'IPN
            boolean success = payTechService.processIPN(ipnData);
            
            if (success) {
                logger.info("PayTech IPN processed successfully for ref_command: {}", ipnData.getRefCommand());
                return ResponseEntity.ok("IPN OK");
            } else {
                logger.warn("PayTech IPN processing failed for ref_command: {}", ipnData.getRefCommand());
                return ResponseEntity.badRequest().body("IPN KO");
            }
            
        } catch (Exception e) {
            logger.error("PayTech IPN Error", e);
            return ResponseEntity.internalServerError().body("IPN ERROR");
        }
    }
    
    /**
     * Page de succès
     */
    @GetMapping("/success")
    public ResponseEntity<Map<String, Object>> paymentSuccess(@RequestParam(required = false) String ref_command) {
        try {
            if (ref_command != null) {
                PayTechTransaction transaction = transactionRepository.findByRefCommand(ref_command);
                if (transaction != null) {
                    return ResponseEntity.ok(Map.of(
                            "status", "success",
                            "message", "Paiement réussi",
                            "transaction", Map.of(
                                    "ref_command", transaction.getRefCommand(),
                                    "amount", transaction.getFormattedAmount(),
                                    "status", transaction.getStatus().name()
                            )
                    ));
                }
            }
            
            return ResponseEntity.ok(Map.of(
                    "status", "success",
                    "message", "Paiement réussi"
            ));
            
        } catch (Exception e) {
            logger.error("Error in payment success page", e);
            return ResponseEntity.internalServerError().body(Map.of(
                    "status", "error",
                    "message", "Erreur interne"
            ));
        }
    }
    
    /**
     * Page d'annulation
     */
    @GetMapping("/cancel")
    public ResponseEntity<Map<String, Object>> paymentCancel(@RequestParam(required = false) String ref_command) {
        try {
            if (ref_command != null) {
                PayTechTransaction transaction = transactionRepository.findByRefCommand(ref_command);
                if (transaction != null && transaction.getStatus() == PayTechTransaction.TransactionStatus.PENDING) {
                    transaction.setStatus(PayTechTransaction.TransactionStatus.CANCELLED);
                    transactionRepository.save(transaction);
                }
            }
            
            return ResponseEntity.ok(Map.of(
                    "status", "cancelled",
                    "message", "Paiement annulé"
            ));
            
        } catch (Exception e) {
            logger.error("Error in payment cancel page", e);
            return ResponseEntity.internalServerError().body(Map.of(
                    "status", "error",
                    "message", "Erreur interne"
            ));
        }
    }
    
    /**
     * Conversion des données brutes en objet IPNData
     */
    private IPNData convertToIPNData(Map<String, Object> rawData) {
        IPNData ipnData = new IPNData();
        
        ipnData.setTypeEvent((String) rawData.get("type_event"));
        ipnData.setCustomField((String) rawData.get("custom_field"));
        ipnData.setRefCommand((String) rawData.get("ref_command"));
        ipnData.setItemName((String) rawData.get("item_name"));
        ipnData.setCurrency((String) rawData.get("currency"));
        ipnData.setCommandName((String) rawData.get("command_name"));
        ipnData.setToken((String) rawData.get("token"));
        ipnData.setEnv((String) rawData.get("env"));
        ipnData.setPaymentMethod((String) rawData.get("payment_method"));
        ipnData.setClientPhone((String) rawData.get("client_phone"));
        ipnData.setApiKeySha256((String) rawData.get("api_key_sha256"));
        ipnData.setApiSecretSha256((String) rawData.get("api_secret_sha256"));
        ipnData.setHmacCompute((String) rawData.get("hmac_compute"));
        
        // Conversion des nombres
        if (rawData.get("item_price") != null) {
            ipnData.setItemPrice(Integer.valueOf(rawData.get("item_price").toString()));
        }
        if (rawData.get("amount") != null) {
            ipnData.setAmount(Integer.valueOf(rawData.get("amount").toString()));
        }
        
        return ipnData;
    }
}
```

## DTOs (Data Transfer Objects)

### PaymentRequest.java

```java
package com.votre.package.dto;

import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.Positive;
import java.util.Map;

public class PaymentRequest {
    
    @NotBlank(message = "Le nom de l'article est requis")
    private String itemName;
    
    @NotNull(message = "Le prix est requis")
    @Positive(message = "Le prix doit être positif")
    private Integer itemPrice;
    
    private String refCommand;
    private String commandName;
    private String currency = "XOF";
    private String paymentMethod;
    private Long userId;
    private boolean mobileIntegration = false;
    private Map<String, String> customField;
    private UserInfo user;
    
    // Constructeurs
    public PaymentRequest() {}
    
    // Getters et Setters
    public String getItemName() { return itemName; }
    public void setItemName(String itemName) { this.itemName = itemName; }
    
    public Integer getItemPrice() { return itemPrice; }
    public void setItemPrice(Integer itemPrice) { this.itemPrice = itemPrice; }
    
    public String getRefCommand() { return refCommand; }
    public void setRefCommand(String refCommand) { this.refCommand = refCommand; }
    
    public String getCommandName() { return commandName; }
    public void setCommandName(String commandName) { this.commandName = commandName; }
    
    public String getCurrency() { return currency; }
    public void setCurrency(String currency) { this.currency = currency; }
    
    public String getPaymentMethod() { return paymentMethod; }
    public void setPaymentMethod(String paymentMethod) { this.paymentMethod = paymentMethod; }
    
    public Long getUserId() { return userId; }
    public void setUserId(Long userId) { this.userId = userId; }
    
    public boolean isMobileIntegration() { return mobileIntegration; }
    public void setMobileIntegration(boolean mobileIntegration) { this.mobileIntegration = mobileIntegration; }
    
    public Map<String, String> getCustomField() { return customField; }
    public void setCustomField(Map<String, String> customField) { this.customField = customField; }
    
    public UserInfo getUser() { return user; }
    public void setUser(UserInfo user) { this.user = user; }
}
```

### UserInfo.java

```java
package com.votre.package.dto;

public class UserInfo {
    private String phoneNumber;
    private String firstName;
    private String lastName;
    private boolean autoSubmit = true;
    
    // Constructeurs
    public UserInfo() {}
    
    public UserInfo(String phoneNumber, String firstName, String lastName) {
        this.phoneNumber = phoneNumber;
        this.firstName = firstName;
        this.lastName = lastName;
    }
    
    // Getters et Setters
    public String getPhoneNumber() { return phoneNumber; }
    public void setPhoneNumber(String phoneNumber) { this.phoneNumber = phoneNumber; }
    
    public String getFirstName() { return firstName; }
    public void setFirstName(String firstName) { this.firstName = firstName; }
    
    public String getLastName() { return lastName; }
    public void setLastName(String lastName) { this.lastName = lastName; }
    
    public boolean isAutoSubmit() { return autoSubmit; }
    public void setAutoSubmit(boolean autoSubmit) { this.autoSubmit = autoSubmit; }
}
```

## Repository

### PayTechTransactionRepository.java

```java
package com.votre.package.repository;

import com.votre.package.model.PayTechTransaction;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.time.LocalDateTime;
import java.util.List;

@Repository
public interface PayTechTransactionRepository extends JpaRepository<PayTechTransaction, Long> {
    
    PayTechTransaction findByRefCommand(String refCommand);
    
    List<PayTechTransaction> findByUserId(Long userId);
    
    List<PayTechTransaction> findByStatus(PayTechTransaction.TransactionStatus status);
    
    @Query("SELECT t FROM PayTechTransaction t WHERE t.status = :status AND t.createdAt < :before")
    List<PayTechTransaction> findByStatusAndCreatedAtBefore(
            @Param("status") PayTechTransaction.TransactionStatus status,
            @Param("before") LocalDateTime before
    );
    
    @Query("SELECT COUNT(t) FROM PayTechTransaction t WHERE t.status = 'COMPLETED' AND t.completedAt >= :from AND t.completedAt <= :to")
    Long countCompletedTransactionsBetween(@Param("from") LocalDateTime from, @Param("to") LocalDateTime to);
    
    @Query("SELECT SUM(t.itemPrice) FROM PayTechTransaction t WHERE t.status = 'COMPLETED' AND t.completedAt >= :from AND t.completedAt <= :to")
    Long sumCompletedAmountBetween(@Param("from") LocalDateTime from, @Param("to") LocalDateTime to);
}
```

## Configuration de sécurité

### SecurityConfig.java

```java
package com.votre.package.config;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.configuration.EnableWebSecurity;
import org.springframework.security.web.SecurityFilterChain;
import org.springframework.security.web.util.matcher.AntPathRequestMatcher;

@Configuration
@EnableWebSecurity
public class SecurityConfig {
    
    @Bean
    public SecurityFilterChain filterChain(HttpSecurity http) throws Exception {
        http
            .csrf(csrf -> csrf
                .ignoringRequestMatchers(
                    new AntPathRequestMatcher("/api/payment/ipn"),
                    new AntPathRequestMatcher("/webhooks/**")
                )
            )
            .authorizeHttpRequests(authz -> authz
                .requestMatchers("/api/payment/ipn").permitAll()
                .requestMatchers("/api/payment/success").permitAll()
                .requestMatchers("/api/payment/cancel").permitAll()
                .requestMatchers("/webhooks/**").permitAll()
                .anyRequest().authenticated()
            );
        
        return http.build();
    }
}
```

## Tests unitaires

### PayTechServiceTest.java

```java
package com.votre.package.service;

import com.votre.package.config.PayTechConfig;
import com.votre.package.dto.PaymentRequest;
import com.votre.package.dto.PaymentResponse;
import com.votre.package.repository.PayTechTransactionRepository;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.extension.ExtendWith;
import org.mockito.InjectMocks;
import org.mockito.Mock;
import org.mockito.junit.jupiter.MockitoExtension;

import static org.junit.jupiter.api.Assertions.*;
import static org.mockito.Mockito.*;

@ExtendWith(MockitoExtension.class)
class PayTechServiceTest {
    
    @Mock
    private PayTechConfig payTechConfig;
    
    @Mock
    private PayTechTransactionRepository transactionRepository;
    
    @InjectMocks
    private PayTechService payTechService;
    
    @BeforeEach
    void setUp() {
        when(payTechConfig.getApiKey()).thenReturn("test_api_key");
        when(payTechConfig.getSecretKey()).thenReturn("test_secret_key");
        when(payTechConfig.getEnvironment()).thenReturn("test");
        when(payTechConfig.getBaseUrl()).thenReturn("https://paytech.sn/api");
    }
    
    @Test
    void testValidatePaymentRequest_ValidRequest_ShouldNotThrow() {
        PaymentRequest request = new PaymentRequest();
        request.setItemName("Test Product");
        request.setItemPrice(5000);
        request.setRefCommand("TEST_CMD_123");
        
        assertDoesNotThrow(() -> {
            // Cette méthode est private, nous testons via requestPayment
            PaymentResponse response = payTechService.requestPayment(request);
        });
    }
    
    @Test
    void testValidatePaymentRequest_InvalidRequest_ShouldThrow() {
        PaymentRequest request = new PaymentRequest();
        request.setItemName("");
        request.setItemPrice(-100);
        
        assertThrows(IllegalArgumentException.class, () -> {
            payTechService.requestPayment(request);
        });
    }
}
```

## Exemple d'utilisation

### Application principale

```java
package com.votre.package;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.context.properties.EnableConfigurationProperties;

@SpringBootApplication
@EnableConfigurationProperties
public class PayTechApplication {
    
    public static void main(String[] args) {
        SpringApplication.run(PayTechApplication.class, args);
    }
}
```

### Client REST exemple

```java
// Exemple d'utilisation du service PayTech
PaymentRequest request = new PaymentRequest();
request.setItemName("iPhone 13 Pro");
request.setItemPrice(650000);
request.setRefCommand("CMD_" + System.currentTimeMillis());
request.setPaymentMethod("Orange Money");

// Informations utilisateur pour préfillage
UserInfo user = new UserInfo();
user.setPhoneNumber("+221771234567");
user.setFirstName("John");
user.setLastName("Doe");
user.setAutoSubmit(true);
request.setUser(user);

// Appel du service
PaymentResponse response = payTechService.requestPayment(request);

if (response.isSuccess()) {
    // Redirection vers PayTech
    String redirectUrl = response.getData().getRedirectUrl();
    // return "redirect:" + redirectUrl;
} else {
    // Gestion des erreurs
    List<String> errors = response.getErrors();
}
```

## Bonnes pratiques Java

### Gestion des erreurs

1. **Exceptions personnalisées** pour les erreurs PayTech
2. **Validation** avec Bean Validation
3. **Logging structuré** avec SLF4J
4. **Retry automatique** avec Spring Retry
5. **Circuit breaker** avec Resilience4j

### Performance

1. **Connection pooling** avec HikariCP
2. **Cache** avec Spring Cache
3. **Async processing** avec @Async
4. **Pagination** pour les listes
5. **Lazy loading** pour les relations JPA

### Sécurité

1. **Validation des entrées** systématique
2. **Chiffrement** des données sensibles
3. **Rate limiting** avec Bucket4j
4. **Audit trail** des transactions
5. **Monitoring** avec Micrometer

Cette intégration Java PayTech offre une solution enterprise-ready avec Spring Boot, une architecture robuste, une sécurité renforcée et une expérience développeur optimale.


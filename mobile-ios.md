# iOS - Intégration PayTech

Cette section détaille l'intégration de PayTech dans une application iOS native, incluant les ViewControllers, la gestion des paiements via WKWebView, et l'architecture MVVM.

## Vue d'ensemble

iOS offre plusieurs approches pour intégrer PayTech :

- **WKWebView** pour l'intégration web (recommandé)
- **SFSafariViewController** pour une expérience navigateur native
- **URL Schemes** pour les retours d'application
- **URLSession** pour les appels API REST

## Prérequis

- **Xcode** : Version 14.0 ou supérieure
- **iOS** : Version 13.0 minimum
- **Swift** : Version 5.0 ou supérieure
- **Permissions** : Network access

## Configuration du projet

### Info.plist

```xml
<!-- Info.plist -->
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
    
    <!-- Permissions -->
    <key>NSCameraUsageDescription</key>
    <string>Cette application utilise la caméra pour scanner les QR codes de paiement</string>
    
    <key>NSPhotoLibraryUsageDescription</key>
    <string>Cette application accède à la galerie pour sélectionner des images</string>
</dict>
</plist>
```

### Podfile

```ruby
# Podfile
platform :ios, '13.0'
use_frameworks!

target 'PayTechApp' do
  # Networking
  pod 'Alamofire', '~> 5.8'
  
  # JSON
  pod 'SwiftyJSON', '~> 5.0'
  
  # UI
  pod 'SnapKit', '~> 5.6'
  pod 'SVProgressHUD', '~> 2.2'
  
  # WebView
  pod 'WebKit'
  
  # Safari Services
  pod 'SafariServices'
  
  target 'PayTechAppTests' do
    inherit! :search_paths
    pod 'Quick', '~> 7.0'
    pod 'Nimble', '~> 12.0'
  end
end
```

## Configuration PayTech

### PayTechConfig.swift

```swift
// PayTechConfig.swift
import Foundation

struct PayTechConfig {
    // URLs de votre backend
    static let apiBaseURL = "https://votre-backend.com/api"
    
    // Configuration PayTech
    static let paymentMethods = [
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
    ]
    
    // URLs de retour mobile
    static let mobileSuccessURL = "paytech://payment/success"
    static let mobileCancelURL = "paytech://payment/cancel"
    static let mobileErrorURL = "paytech://payment/error"
    
    // URLs de retour web (fallback)
    static let webSuccessURL = "https://paytech.sn/mobile/success"
    static let webCancelURL = "https://paytech.sn/mobile/cancel"
    
    // Configuration WebView
    static let webViewTimeout: TimeInterval = 30.0
    static let webViewUserAgent = "PayTechiOS/1.0"
    
    // Préfillage automatique
    static let autoSubmitEnabled = true
    static let prefillEnabled = true
    
    // Configuration de l'environnement
    static let isProduction = false
    static let debugMode = !isProduction
}
```

## Modèles de données

### PaymentModels.swift

```swift
// PaymentModels.swift
import Foundation

// MARK: - Payment Request
struct PaymentRequest: Codable {
    let itemName: String
    let itemPrice: Int
    let refCommand: String?
    let commandName: String?
    let currency: String
    let paymentMethod: String?
    let mobileIntegration: Bool
    let customField: [String: Any]?
    let user: UserInfo?
    
    enum CodingKeys: String, CodingKey {
        case itemName = "item_name"
        case itemPrice = "item_price"
        case refCommand = "ref_command"
        case commandName = "command_name"
        case currency
        case paymentMethod = "payment_method"
        case mobileIntegration = "mobile_integration"
        case customField = "custom_field"
        case user
    }
    
    init(itemName: String, 
         itemPrice: Int, 
         refCommand: String? = nil,
         commandName: String? = nil,
         currency: String = "XOF",
         paymentMethod: String? = nil,
         mobileIntegration: Bool = true,
         customField: [String: Any]? = nil,
         user: UserInfo? = nil) {
        self.itemName = itemName
        self.itemPrice = itemPrice
        self.refCommand = refCommand
        self.commandName = commandName
        self.currency = currency
        self.paymentMethod = paymentMethod
        self.mobileIntegration = mobileIntegration
        self.customField = customField
        self.user = user
    }
    
    func encode(to encoder: Encoder) throws {
        var container = encoder.container(keyedBy: CodingKeys.self)
        try container.encode(itemName, forKey: .itemName)
        try container.encode(itemPrice, forKey: .itemPrice)
        try container.encodeIfPresent(refCommand, forKey: .refCommand)
        try container.encodeIfPresent(commandName, forKey: .commandName)
        try container.encode(currency, forKey: .currency)
        try container.encodeIfPresent(paymentMethod, forKey: .paymentMethod)
        try container.encode(mobileIntegration, forKey: .mobileIntegration)
        try container.encodeIfPresent(user, forKey: .user)
        
        // Gestion spéciale pour customField
        if let customField = customField {
            let data = try JSONSerialization.data(withJSONObject: customField)
            let jsonString = String(data: data, encoding: .utf8) ?? "{}"
            try container.encode(jsonString, forKey: .customField)
        }
    }
}

// MARK: - User Info
struct UserInfo: Codable {
    let phoneNumber: String?
    let firstName: String?
    let lastName: String?
    let autoSubmit: Bool
    
    enum CodingKeys: String, CodingKey {
        case phoneNumber = "phone_number"
        case firstName = "first_name"
        case lastName = "last_name"
        case autoSubmit = "auto_submit"
    }
}

// MARK: - Payment Response
struct PaymentResponse: Codable {
    let success: Bool
    let data: PayTechData?
    let transaction: Transaction?
    let error: String?
}

struct PayTechData: Codable {
    let token: String
    let redirectUrl: String
    
    enum CodingKeys: String, CodingKey {
        case token
        case redirectUrl = "redirect_url"
    }
}

// MARK: - Transaction
struct Transaction: Codable {
    let id: String
    let refCommand: String
    let itemName: String
    let itemPrice: Int
    let currency: String
    let paymentMethod: String?
    let status: TransactionStatus
    let paytechToken: String?
    let redirectUrl: String?
    let userId: String?
    let completedAt: String?
    let createdAt: String
    let updatedAt: String
    
    enum CodingKeys: String, CodingKey {
        case id
        case refCommand = "ref_command"
        case itemName = "item_name"
        case itemPrice = "item_price"
        case currency
        case paymentMethod = "payment_method"
        case status
        case paytechToken = "paytech_token"
        case redirectUrl = "redirect_url"
        case userId = "user_id"
        case completedAt = "completed_at"
        case createdAt = "created_at"
        case updatedAt = "updated_at"
    }
}

// MARK: - Transaction Status
enum TransactionStatus: String, Codable, CaseIterable {
    case pending = "PENDING"
    case completed = "COMPLETED"
    case failed = "FAILED"
    case cancelled = "CANCELLED"
    
    var displayName: String {
        switch self {
        case .pending: return "En attente"
        case .completed: return "Complété"
        case .failed: return "Échoué"
        case .cancelled: return "Annulé"
        }
    }
    
    var emoji: String {
        switch self {
        case .pending: return "⏳"
        case .completed: return "✅"
        case .failed: return "❌"
        case .cancelled: return "🚫"
        }
    }
}

struct TransactionStatusResponse: Codable {
    let refCommand: String
    let status: TransactionStatus
    let amount: String
    let completedAt: String?
    let createdAt: String
    
    enum CodingKeys: String, CodingKey {
        case refCommand = "ref_command"
        case status
        case amount
        case completedAt = "completed_at"
        case createdAt = "created_at"
    }
}
```

## Service API PayTech

### PayTechAPIService.swift

```swift
// PayTechAPIService.swift
import Foundation
import Alamofire

protocol PayTechAPIServiceProtocol {
    func requestPayment(_ request: PaymentRequest, completion: @escaping (Result<PaymentResponse, Error>) -> Void)
    func getTransactionStatus(_ refCommand: String, completion: @escaping (Result<TransactionStatusResponse, Error>) -> Void)
    func getUserTransactions(_ userId: String, completion: @escaping (Result<[TransactionStatusResponse], Error>) -> Void)
}

class PayTechAPIService: PayTechAPIServiceProtocol {
    
    static let shared = PayTechAPIService()
    
    private let session: Session
    private let baseURL: String
    
    private init() {
        self.baseURL = PayTechConfig.apiBaseURL
        
        let configuration = URLSessionConfiguration.default
        configuration.timeoutIntervalForRequest = PayTechConfig.webViewTimeout
        configuration.timeoutIntervalForResource = PayTechConfig.webViewTimeout
        
        self.session = Session(configuration: configuration)
    }
    
    // MARK: - Payment Request
    func requestPayment(_ request: PaymentRequest, completion: @escaping (Result<PaymentResponse, Error>) -> Void) {
        let url = "\(baseURL)/payment/request"
        
        session.request(url,
                       method: .post,
                       parameters: request,
                       encoder: JSONParameterEncoder.default,
                       headers: ["Content-Type": "application/json"])
            .validate()
            .responseDecodable(of: PaymentResponse.self) { response in
                switch response.result {
                case .success(let paymentResponse):
                    if paymentResponse.success {
                        completion(.success(paymentResponse))
                    } else {
                        let error = NSError(domain: "PayTechError", 
                                          code: -1, 
                                          userInfo: [NSLocalizedDescriptionKey: paymentResponse.error ?? "Erreur de paiement"])
                        completion(.failure(error))
                    }
                case .failure(let error):
                    completion(.failure(error))
                }
            }
    }
    
    // MARK: - Transaction Status
    func getTransactionStatus(_ refCommand: String, completion: @escaping (Result<TransactionStatusResponse, Error>) -> Void) {
        let url = "\(baseURL)/payment/status/\(refCommand)"
        
        session.request(url, method: .get)
            .validate()
            .responseDecodable(of: TransactionStatusResponse.self) { response in
                completion(response.result)
            }
    }
    
    // MARK: - User Transactions
    func getUserTransactions(_ userId: String, completion: @escaping (Result<[TransactionStatusResponse], Error>) -> Void) {
        let url = "\(baseURL)/payment/history/\(userId)"
        
        session.request(url, method: .get)
            .validate()
            .responseDecodable(of: [TransactionStatusResponse].self) { response in
                completion(response.result)
            }
    }
}
```

## ViewModel

### PaymentViewModel.swift

```swift
// PaymentViewModel.swift
import Foundation
import Combine

class PaymentViewModel: ObservableObject {
    
    // MARK: - Published Properties
    @Published var isLoading = false
    @Published var errorMessage: String?
    @Published var successMessage: String?
    @Published var currentTransaction: Transaction?
    @Published var transactionStatus: TransactionStatusResponse?
    
    // MARK: - Private Properties
    private let apiService: PayTechAPIServiceProtocol
    private var cancellables = Set<AnyCancellable>()
    
    // MARK: - Initialization
    init(apiService: PayTechAPIServiceProtocol = PayTechAPIService.shared) {
        self.apiService = apiService
    }
    
    // MARK: - Payment Methods
    func requestPayment(itemName: String,
                       itemPrice: Int,
                       paymentMethod: String? = nil,
                       userInfo: UserInfo? = nil) {
        
        guard !itemName.isEmpty, itemPrice >= 100 else {
            errorMessage = "Veuillez remplir tous les champs obligatoires et vérifier le montant minimum (100 FCFA)"
            return
        }
        
        isLoading = true
        errorMessage = nil
        
        let request = PaymentRequest(
            itemName: itemName,
            itemPrice: itemPrice,
            refCommand: generateRefCommand(),
            commandName: itemName,
            currency: "XOF",
            paymentMethod: paymentMethod,
            mobileIntegration: true,
            customField: [
                "source": "ios_app",
                "version": "1.0",
                "timestamp": Date().timeIntervalSince1970
            ],
            user: userInfo
        )
        
        apiService.requestPayment(request) { [weak self] result in
            DispatchQueue.main.async {
                self?.isLoading = false
                
                switch result {
                case .success(let response):
                    self?.currentTransaction = response.transaction
                    self?.successMessage = "Redirection vers PayTech..."
                    
                    // Notifier le succès pour déclencher la navigation
                    NotificationCenter.default.post(
                        name: .paymentInitiated,
                        object: response.data
                    )
                    
                case .failure(let error):
                    self?.errorMessage = error.localizedDescription
                }
            }
        }
    }
    
    func checkTransactionStatus(_ refCommand: String) {
        isLoading = true
        errorMessage = nil
        
        apiService.getTransactionStatus(refCommand) { [weak self] result in
            DispatchQueue.main.async {
                self?.isLoading = false
                
                switch result {
                case .success(let status):
                    self?.transactionStatus = status
                    self?.successMessage = "Statut récupéré"
                    
                case .failure(let error):
                    self?.errorMessage = error.localizedDescription
                }
            }
        }
    }
    
    func clearMessages() {
        errorMessage = nil
        successMessage = nil
    }
    
    func clearAll() {
        isLoading = false
        errorMessage = nil
        successMessage = nil
        currentTransaction = nil
        transactionStatus = nil
    }
    
    // MARK: - Private Methods
    private func generateRefCommand() -> String {
        let timestamp = Int(Date().timeIntervalSince1970)
        let random = UUID().uuidString.prefix(8)
        return "CMD_\(timestamp)_\(random)"
    }
}

// MARK: - Notification Names
extension Notification.Name {
    static let paymentInitiated = Notification.Name("paymentInitiated")
    static let paymentCompleted = Notification.Name("paymentCompleted")
    static let paymentCancelled = Notification.Name("paymentCancelled")
    static let paymentFailed = Notification.Name("paymentFailed")
}
```

## ViewControllers

### PaymentViewController.swift

```swift
// PaymentViewController.swift
import UIKit
import SnapKit
import SVProgressHUD
import Combine

class PaymentViewController: UIViewController {
    
    // MARK: - UI Components
    private lazy var scrollView: UIScrollView = {
        let scrollView = UIScrollView()
        scrollView.showsVerticalScrollIndicator = false
        return scrollView
    }()
    
    private lazy var contentView: UIView = {
        let view = UIView()
        return view
    }()
    
    private lazy var titleLabel: UILabel = {
        let label = UILabel()
        label.text = "Paiement PayTech"
        label.font = UIFont.boldSystemFont(ofSize: 24)
        label.textAlignment = .center
        return label
    }()
    
    private lazy var productCardView: UIView = {
        let view = UIView()
        view.backgroundColor = .systemBackground
        view.layer.cornerRadius = 12
        view.layer.shadowColor = UIColor.black.cgColor
        view.layer.shadowOffset = CGSize(width: 0, height: 2)
        view.layer.shadowRadius = 4
        view.layer.shadowOpacity = 0.1
        return view
    }()
    
    private lazy var itemNameTextField: UITextField = {
        let textField = UITextField()
        textField.placeholder = "Produit/Service *"
        textField.borderStyle = .roundedRect
        textField.font = UIFont.systemFont(ofSize: 16)
        return textField
    }()
    
    private lazy var itemPriceTextField: UITextField = {
        let textField = UITextField()
        textField.placeholder = "Montant (FCFA) *"
        textField.borderStyle = .roundedRect
        textField.keyboardType = .numberPad
        textField.font = UIFont.systemFont(ofSize: 16)
        return textField
    }()
    
    private lazy var paymentMethodPicker: UIPickerView = {
        let picker = UIPickerView()
        picker.delegate = self
        picker.dataSource = self
        return picker
    }()
    
    private lazy var paymentMethodTextField: UITextField = {
        let textField = UITextField()
        textField.placeholder = "Méthode de paiement (optionnel)"
        textField.borderStyle = .roundedRect
        textField.inputView = paymentMethodPicker
        textField.font = UIFont.systemFont(ofSize: 16)
        return textField
    }()
    
    private lazy var userInfoCardView: UIView = {
        let view = UIView()
        view.backgroundColor = .systemBackground
        view.layer.cornerRadius = 12
        view.layer.shadowColor = UIColor.black.cgColor
        view.layer.shadowOffset = CGSize(width: 0, height: 2)
        view.layer.shadowRadius = 4
        view.layer.shadowOpacity = 0.1
        return view
    }()
    
    private lazy var firstNameTextField: UITextField = {
        let textField = UITextField()
        textField.placeholder = "Prénom"
        textField.borderStyle = .roundedRect
        textField.font = UIFont.systemFont(ofSize: 16)
        return textField
    }()
    
    private lazy var lastNameTextField: UITextField = {
        let textField = UITextField()
        textField.placeholder = "Nom"
        textField.borderStyle = .roundedRect
        textField.font = UIFont.systemFont(ofSize: 16)
        return textField
    }()
    
    private lazy var phoneTextField: UITextField = {
        let textField = UITextField()
        textField.placeholder = "Téléphone"
        textField.borderStyle = .roundedRect
        textField.keyboardType = .phonePad
        textField.font = UIFont.systemFont(ofSize: 16)
        return textField
    }()
    
    private lazy var autoSubmitSwitch: UISwitch = {
        let switchControl = UISwitch()
        switchControl.isOn = PayTechConfig.autoSubmitEnabled
        return switchControl
    }()
    
    private lazy var mobileIntegrationSwitch: UISwitch = {
        let switchControl = UISwitch()
        switchControl.isOn = true
        return switchControl
    }()
    
    private lazy var payButton: UIButton = {
        let button = UIButton(type: .system)
        button.setTitle("Payer avec PayTech", for: .normal)
        button.titleLabel?.font = UIFont.boldSystemFont(ofSize: 18)
        button.backgroundColor = .systemBlue
        button.setTitleColor(.white, for: .normal)
        button.layer.cornerRadius = 12
        button.addTarget(self, action: #selector(payButtonTapped), for: .touchUpInside)
        return button
    }()
    
    private lazy var securityLabel: UILabel = {
        let label = UILabel()
        label.text = "🔒 Paiement 100% sécurisé\nPropulsé par PayTech Sénégal"
        label.font = UIFont.systemFont(ofSize: 12)
        label.textAlignment = .center
        label.numberOfLines = 0
        label.alpha = 0.7
        return label
    }()
    
    // MARK: - Properties
    private let viewModel = PaymentViewModel()
    private var cancellables = Set<AnyCancellable>()
    private let paymentMethods = ["Choisir sur PayTech"] + PayTechConfig.paymentMethods
    
    // MARK: - Lifecycle
    override func viewDidLoad() {
        super.viewDidLoad()
        setupUI()
        setupBindings()
        setupNotifications()
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        view.endEditing(true)
    }
    
    // MARK: - Setup Methods
    private func setupUI() {
        view.backgroundColor = .systemGroupedBackground
        title = "Paiement"
        
        // Navigation
        navigationItem.leftBarButtonItem = UIBarButtonItem(
            barButtonSystemItem: .cancel,
            target: self,
            action: #selector(cancelButtonTapped)
        )
        
        // Add subviews
        view.addSubview(scrollView)
        scrollView.addSubview(contentView)
        
        contentView.addSubview(titleLabel)
        contentView.addSubview(productCardView)
        contentView.addSubview(userInfoCardView)
        contentView.addSubview(payButton)
        contentView.addSubview(securityLabel)
        
        // Product card content
        productCardView.addSubview(itemNameTextField)
        productCardView.addSubview(itemPriceTextField)
        productCardView.addSubview(paymentMethodTextField)
        
        // User info card content
        userInfoCardView.addSubview(firstNameTextField)
        userInfoCardView.addSubview(lastNameTextField)
        userInfoCardView.addSubview(phoneTextField)
        userInfoCardView.addSubview(autoSubmitSwitch)
        userInfoCardView.addSubview(mobileIntegrationSwitch)
        
        setupConstraints()
    }
    
    private func setupConstraints() {
        scrollView.snp.makeConstraints { make in
            make.edges.equalTo(view.safeAreaLayoutGuide)
        }
        
        contentView.snp.makeConstraints { make in
            make.edges.equalToSuperview()
            make.width.equalToSuperview()
        }
        
        titleLabel.snp.makeConstraints { make in
            make.top.equalToSuperview().offset(20)
            make.leading.trailing.equalToSuperview().inset(20)
        }
        
        productCardView.snp.makeConstraints { make in
            make.top.equalTo(titleLabel.snp.bottom).offset(20)
            make.leading.trailing.equalToSuperview().inset(20)
        }
        
        itemNameTextField.snp.makeConstraints { make in
            make.top.equalToSuperview().offset(20)
            make.leading.trailing.equalToSuperview().inset(16)
            make.height.equalTo(44)
        }
        
        itemPriceTextField.snp.makeConstraints { make in
            make.top.equalTo(itemNameTextField.snp.bottom).offset(16)
            make.leading.trailing.equalToSuperview().inset(16)
            make.height.equalTo(44)
        }
        
        paymentMethodTextField.snp.makeConstraints { make in
            make.top.equalTo(itemPriceTextField.snp.bottom).offset(16)
            make.leading.trailing.equalToSuperview().inset(16)
            make.height.equalTo(44)
            make.bottom.equalToSuperview().offset(-20)
        }
        
        userInfoCardView.snp.makeConstraints { make in
            make.top.equalTo(productCardView.snp.bottom).offset(20)
            make.leading.trailing.equalToSuperview().inset(20)
        }
        
        firstNameTextField.snp.makeConstraints { make in
            make.top.equalToSuperview().offset(20)
            make.leading.equalToSuperview().offset(16)
            make.trailing.equalTo(userInfoCardView.snp.centerX).offset(-8)
            make.height.equalTo(44)
        }
        
        lastNameTextField.snp.makeConstraints { make in
            make.top.equalToSuperview().offset(20)
            make.leading.equalTo(userInfoCardView.snp.centerX).offset(8)
            make.trailing.equalToSuperview().offset(-16)
            make.height.equalTo(44)
        }
        
        phoneTextField.snp.makeConstraints { make in
            make.top.equalTo(firstNameTextField.snp.bottom).offset(16)
            make.leading.trailing.equalToSuperview().inset(16)
            make.height.equalTo(44)
        }
        
        autoSubmitSwitch.snp.makeConstraints { make in
            make.top.equalTo(phoneTextField.snp.bottom).offset(20)
            make.leading.equalToSuperview().offset(16)
        }
        
        mobileIntegrationSwitch.snp.makeConstraints { make in
            make.top.equalTo(autoSubmitSwitch.snp.bottom).offset(16)
            make.leading.equalToSuperview().offset(16)
            make.bottom.equalToSuperview().offset(-20)
        }
        
        payButton.snp.makeConstraints { make in
            make.top.equalTo(userInfoCardView.snp.bottom).offset(30)
            make.leading.trailing.equalToSuperview().inset(20)
            make.height.equalTo(50)
        }
        
        securityLabel.snp.makeConstraints { make in
            make.top.equalTo(payButton.snp.bottom).offset(20)
            make.leading.trailing.equalToSuperview().inset(20)
            make.bottom.equalToSuperview().offset(-20)
        }
    }
    
    private func setupBindings() {
        viewModel.$isLoading
            .receive(on: DispatchQueue.main)
            .sink { [weak self] isLoading in
                if isLoading {
                    SVProgressHUD.show(withStatus: "Traitement...")
                    self?.payButton.isEnabled = false
                    self?.payButton.setTitle("Traitement...", for: .normal)
                } else {
                    SVProgressHUD.dismiss()
                    self?.payButton.isEnabled = true
                    self?.payButton.setTitle("Payer avec PayTech", for: .normal)
                }
            }
            .store(in: &cancellables)
        
        viewModel.$errorMessage
            .receive(on: DispatchQueue.main)
            .sink { [weak self] errorMessage in
                if let error = errorMessage {
                    self?.showAlert(title: "Erreur", message: error)
                    self?.viewModel.clearMessages()
                }
            }
            .store(in: &cancellables)
        
        viewModel.$successMessage
            .receive(on: DispatchQueue.main)
            .sink { [weak self] successMessage in
                if let success = successMessage {
                    SVProgressHUD.showSuccess(withStatus: success)
                    self?.viewModel.clearMessages()
                }
            }
            .store(in: &cancellables)
    }
    
    private func setupNotifications() {
        NotificationCenter.default.addObserver(
            self,
            selector: #selector(paymentInitiated(_:)),
            name: .paymentInitiated,
            object: nil
        )
    }
    
    // MARK: - Actions
    @objc private func payButtonTapped() {
        view.endEditing(true)
        
        guard let itemName = itemNameTextField.text, !itemName.isEmpty else {
            showAlert(title: "Erreur", message: "Le nom du produit est requis")
            return
        }
        
        guard let itemPriceText = itemPriceTextField.text,
              let itemPrice = Int(itemPriceText), itemPrice >= 100 else {
            showAlert(title: "Erreur", message: "Le montant minimum est de 100 FCFA")
            return
        }
        
        let paymentMethod = paymentMethodTextField.text?.isEmpty == false ? 
            paymentMethodTextField.text : nil
        
        let userInfo = createUserInfo()
        
        viewModel.requestPayment(
            itemName: itemName,
            itemPrice: itemPrice,
            paymentMethod: paymentMethod,
            userInfo: userInfo
        )
    }
    
    @objc private func cancelButtonTapped() {
        dismiss(animated: true)
    }
    
    @objc private func paymentInitiated(_ notification: Notification) {
        guard let payTechData = notification.object as? PayTechData else { return }
        
        let webViewVC = PayTechWebViewController(
            redirectURL: payTechData.redirectUrl,
            refCommand: viewModel.currentTransaction?.refCommand
        )
        
        let navController = UINavigationController(rootViewController: webViewVC)
        navController.modalPresentationStyle = .fullScreen
        
        present(navController, animated: true)
    }
    
    // MARK: - Helper Methods
    private func createUserInfo() -> UserInfo? {
        let phone = phoneTextField.text?.trimmingCharacters(in: .whitespacesAndNewlines)
        let firstName = firstNameTextField.text?.trimmingCharacters(in: .whitespacesAndNewlines)
        let lastName = lastNameTextField.text?.trimmingCharacters(in: .whitespacesAndNewlines)
        
        if phone?.isEmpty != false && firstName?.isEmpty != false && lastName?.isEmpty != false {
            return nil
        }
        
        return UserInfo(
            phoneNumber: phone?.isEmpty == false ? phone : nil,
            firstName: firstName?.isEmpty == false ? firstName : nil,
            lastName: lastName?.isEmpty == false ? lastName : nil,
            autoSubmit: autoSubmitSwitch.isOn
        )
    }
    
    private func showAlert(title: String, message: String) {
        let alert = UIAlertController(title: title, message: message, preferredStyle: .alert)
        alert.addAction(UIAlertAction(title: "OK", style: .default))
        present(alert, animated: true)
    }
}

// MARK: - UIPickerView DataSource & Delegate
extension PaymentViewController: UIPickerViewDataSource, UIPickerViewDelegate {
    func numberOfComponents(in pickerView: UIPickerView) -> Int {
        return 1
    }
    
    func pickerView(_ pickerView: UIPickerView, numberOfRowsInComponent component: Int) -> Int {
        return paymentMethods.count
    }
    
    func pickerView(_ pickerView: UIPickerView, titleForRow row: Int, forComponent component: Int) -> String? {
        return paymentMethods[row]
    }
    
    func pickerView(_ pickerView: UIPickerView, didSelectRow row: Int, inComponent component: Int) {
        if row == 0 {
            paymentMethodTextField.text = ""
        } else {
            paymentMethodTextField.text = paymentMethods[row]
        }
    }
}
```

### PayTechWebViewController.swift

```swift
// PayTechWebViewController.swift
import UIKit
import WebKit
import SVProgressHUD

class PayTechWebViewController: UIViewController {
    
    // MARK: - UI Components
    private lazy var webView: WKWebView = {
        let configuration = WKWebViewConfiguration()
        configuration.websiteDataStore = .default()
        
        let webView = WKWebView(frame: .zero, configuration: configuration)
        webView.navigationDelegate = self
        webView.uiDelegate = self
        webView.allowsBackForwardNavigationGestures = true
        
        // Configuration personnalisée
        webView.customUserAgent = PayTechConfig.webViewUserAgent
        webView.scrollView.contentInsetAdjustmentBehavior = .automatic
        
        return webView
    }()
    
    private lazy var progressView: UIProgressView = {
        let progressView = UIProgressView(progressViewStyle: .default)
        progressView.progressTintColor = .systemBlue
        progressView.trackTintColor = .systemGray5
        return progressView
    }()
    
    private lazy var errorView: UIView = {
        let view = UIView()
        view.backgroundColor = .systemBackground
        view.isHidden = true
        return view
    }()
    
    private lazy var errorLabel: UILabel = {
        let label = UILabel()
        label.text = "Erreur de chargement"
        label.font = UIFont.systemFont(ofSize: 16)
        label.textAlignment = .center
        label.numberOfLines = 0
        return label
    }()
    
    private lazy var retryButton: UIButton = {
        let button = UIButton(type: .system)
        button.setTitle("Réessayer", for: .normal)
        button.titleLabel?.font = UIFont.boldSystemFont(ofSize: 16)
        button.backgroundColor = .systemBlue
        button.setTitleColor(.white, for: .normal)
        button.layer.cornerRadius = 8
        button.addTarget(self, action: #selector(retryButtonTapped), for: .touchUpInside)
        return button
    }()
    
    // MARK: - Properties
    private let redirectURL: String
    private let refCommand: String?
    private var progressObservation: NSKeyValueObservation?
    
    // MARK: - Initialization
    init(redirectURL: String, refCommand: String?) {
        self.redirectURL = redirectURL
        self.refCommand = refCommand
        super.init(nibName: nil, bundle: nil)
    }
    
    required init?(coder: NSCoder) {
        fatalError("init(coder:) has not been implemented")
    }
    
    // MARK: - Lifecycle
    override func viewDidLoad() {
        super.viewDidLoad()
        setupUI()
        setupObservers()
        loadPayTechURL()
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        progressObservation?.invalidate()
    }
    
    // MARK: - Setup Methods
    private func setupUI() {
        view.backgroundColor = .systemBackground
        title = "Paiement PayTech"
        
        // Navigation
        navigationItem.leftBarButtonItem = UIBarButtonItem(
            barButtonSystemItem: .cancel,
            target: self,
            action: #selector(cancelButtonTapped)
        )
        
        navigationItem.rightBarButtonItem = UIBarButtonItem(
            barButtonSystemItem: .refresh,
            target: self,
            action: #selector(refreshButtonTapped)
        )
        
        // Add subviews
        view.addSubview(webView)
        view.addSubview(progressView)
        view.addSubview(errorView)
        
        errorView.addSubview(errorLabel)
        errorView.addSubview(retryButton)
        
        setupConstraints()
    }
    
    private func setupConstraints() {
        webView.snp.makeConstraints { make in
            make.top.equalTo(progressView.snp.bottom)
            make.leading.trailing.bottom.equalTo(view.safeAreaLayoutGuide)
        }
        
        progressView.snp.makeConstraints { make in
            make.top.equalTo(view.safeAreaLayoutGuide)
            make.leading.trailing.equalToSuperview()
            make.height.equalTo(2)
        }
        
        errorView.snp.makeConstraints { make in
            make.edges.equalTo(webView)
        }
        
        errorLabel.snp.makeConstraints { make in
            make.center.equalToSuperview()
            make.leading.trailing.equalToSuperview().inset(20)
        }
        
        retryButton.snp.makeConstraints { make in
            make.top.equalTo(errorLabel.snp.bottom).offset(20)
            make.centerX.equalToSuperview()
            make.width.equalTo(120)
            make.height.equalTo(44)
        }
    }
    
    private func setupObservers() {
        progressObservation = webView.observe(\.estimatedProgress, options: .new) { [weak self] _, _ in
            DispatchQueue.main.async {
                self?.updateProgress()
            }
        }
    }
    
    private func loadPayTechURL() {
        guard let url = URL(string: redirectURL) else {
            showError("URL invalide")
            return
        }
        
        showLoading(true)
        let request = URLRequest(url: url, timeoutInterval: PayTechConfig.webViewTimeout)
        webView.load(request)
    }
    
    // MARK: - Actions
    @objc private func cancelButtonTapped() {
        let alert = UIAlertController(
            title: "Annuler le paiement",
            message: "Êtes-vous sûr de vouloir annuler ce paiement ?",
            preferredStyle: .alert
        )
        
        alert.addAction(UIAlertAction(title: "Continuer", style: .cancel))
        alert.addAction(UIAlertAction(title: "Annuler", style: .destructive) { [weak self] _ in
            self?.handlePaymentCancel()
        })
        
        present(alert, animated: true)
    }
    
    @objc private func refreshButtonTapped() {
        webView.reload()
    }
    
    @objc private func retryButtonTapped() {
        loadPayTechURL()
    }
    
    // MARK: - Helper Methods
    private func updateProgress() {
        let progress = Float(webView.estimatedProgress)
        progressView.setProgress(progress, animated: true)
        
        if progress >= 1.0 {
            DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                self.progressView.isHidden = true
            }
        } else {
            progressView.isHidden = false
        }
    }
    
    private func showLoading(_ show: Bool) {
        if show {
            SVProgressHUD.show()
        } else {
            SVProgressHUD.dismiss()
        }
        
        errorView.isHidden = show
        webView.isHidden = !show && !errorView.isHidden
    }
    
    private func showError(_ message: String) {
        showLoading(false)
        errorLabel.text = message
        errorView.isHidden = false
        webView.isHidden = true
    }
    
    private func handlePaymentSuccess(_ url: String) {
        dismiss(animated: true) {
            NotificationCenter.default.post(
                name: .paymentCompleted,
                object: ["url": url, "refCommand": self.refCommand ?? ""]
            )
        }
    }
    
    private func handlePaymentCancel() {
        dismiss(animated: true) {
            NotificationCenter.default.post(
                name: .paymentCancelled,
                object: ["refCommand": self.refCommand ?? ""]
            )
        }
    }
    
    private func handlePaymentError(_ url: String) {
        dismiss(animated: true) {
            NotificationCenter.default.post(
                name: .paymentFailed,
                object: ["url": url, "refCommand": self.refCommand ?? ""]
            )
        }
    }
}

// MARK: - WKNavigationDelegate
extension PayTechWebViewController: WKNavigationDelegate {
    
    func webView(_ webView: WKWebView, decidePolicyFor navigationAction: WKNavigationAction, decisionHandler: @escaping (WKNavigationActionPolicy) -> Void) {
        
        guard let url = navigationAction.request.url?.absoluteString else {
            decisionHandler(.allow)
            return
        }
        
        // Vérifier les URLs de retour mobile
        if url.hasPrefix(PayTechConfig.mobileSuccessURL) {
            handlePaymentSuccess(url)
            decisionHandler(.cancel)
            return
        }
        
        if url.hasPrefix(PayTechConfig.mobileCancelURL) {
            handlePaymentCancel()
            decisionHandler(.cancel)
            return
        }
        
        if url.hasPrefix(PayTechConfig.mobileErrorURL) {
            handlePaymentError(url)
            decisionHandler(.cancel)
            return
        }
        
        // Vérifier les URLs de retour web (fallback)
        if url.contains("paytech.sn/mobile/success") {
            handlePaymentSuccess(url)
            decisionHandler(.cancel)
            return
        }
        
        if url.contains("paytech.sn/mobile/cancel") {
            handlePaymentCancel()
            decisionHandler(.cancel)
            return
        }
        
        // Permettre la navigation normale pour les URLs PayTech
        if url.contains("paytech.sn") {
            decisionHandler(.allow)
            return
        }
        
        // Bloquer les redirections externes
        decisionHandler(.cancel)
    }
    
    func webView(_ webView: WKWebView, didStartProvisionalNavigation navigation: WKNavigation!) {
        showLoading(true)
    }
    
    func webView(_ webView: WKWebView, didFinish navigation: WKNavigation!) {
        showLoading(false)
    }
    
    func webView(_ webView: WKWebView, didFail navigation: WKNavigation!, withError error: Error) {
        showError("Erreur de chargement: \(error.localizedDescription)")
    }
    
    func webView(_ webView: WKWebView, didFailProvisionalNavigation navigation: WKNavigation!, withError error: Error) {
        showError("Erreur de connexion: \(error.localizedDescription)")
    }
}

// MARK: - WKUIDelegate
extension PayTechWebViewController: WKUIDelegate {
    
    func webView(_ webView: WKWebView, runJavaScriptAlertPanelWithMessage message: String, initiatedByFrame frame: WKFrameInfo, completionHandler: @escaping () -> Void) {
        
        let alert = UIAlertController(title: "PayTech", message: message, preferredStyle: .alert)
        alert.addAction(UIAlertAction(title: "OK", style: .default) { _ in
            completionHandler()
        })
        
        present(alert, animated: true)
    }
    
    func webView(_ webView: WKWebView, runJavaScriptConfirmPanelWithMessage message: String, initiatedByFrame frame: WKFrameInfo, completionHandler: @escaping (Bool) -> Void) {
        
        let alert = UIAlertController(title: "PayTech", message: message, preferredStyle: .alert)
        
        alert.addAction(UIAlertAction(title: "Annuler", style: .cancel) { _ in
            completionHandler(false)
        })
        
        alert.addAction(UIAlertAction(title: "OK", style: .default) { _ in
            completionHandler(true)
        })
        
        present(alert, animated: true)
    }
}
```

### PaymentResultViewController.swift

```swift
// PaymentResultViewController.swift
import UIKit
import SnapKit
import Combine

enum PaymentResult {
    case success
    case cancel
    case error
    
    var title: String {
        switch self {
        case .success: return "Paiement réussi"
        case .cancel: return "Paiement annulé"
        case .error: return "Erreur de paiement"
        }
    }
    
    var message: String {
        switch self {
        case .success: return "Votre paiement a été initié avec succès. Veuillez patienter pendant que nous vérifions le statut."
        case .cancel: return "Votre paiement a été annulé. Aucun montant n'a été débité."
        case .error: return "Une erreur est survenue lors du traitement de votre paiement."
        }
    }
    
    var icon: String {
        switch self {
        case .success: return "checkmark.circle.fill"
        case .cancel: return "xmark.circle.fill"
        case .error: return "exclamationmark.triangle.fill"
        }
    }
    
    var color: UIColor {
        switch self {
        case .success: return .systemGreen
        case .cancel: return .systemOrange
        case .error: return .systemRed
        }
    }
}

class PaymentResultViewController: UIViewController {
    
    // MARK: - UI Components
    private lazy var scrollView: UIScrollView = {
        let scrollView = UIScrollView()
        scrollView.showsVerticalScrollIndicator = false
        return scrollView
    }()
    
    private lazy var contentView: UIView = {
        let view = UIView()
        return view
    }()
    
    private lazy var resultCardView: UIView = {
        let view = UIView()
        view.backgroundColor = .systemBackground
        view.layer.cornerRadius = 16
        view.layer.shadowColor = UIColor.black.cgColor
        view.layer.shadowOffset = CGSize(width: 0, height: 4)
        view.layer.shadowRadius = 8
        view.layer.shadowOpacity = 0.1
        return view
    }()
    
    private lazy var iconImageView: UIImageView = {
        let imageView = UIImageView()
        imageView.contentMode = .scaleAspectFit
        imageView.tintColor = result.color
        return imageView
    }()
    
    private lazy var titleLabel: UILabel = {
        let label = UILabel()
        label.font = UIFont.boldSystemFont(ofSize: 24)
        label.textAlignment = .center
        label.numberOfLines = 0
        return label
    }()
    
    private lazy var messageLabel: UILabel = {
        let label = UILabel()
        label.font = UIFont.systemFont(ofSize: 16)
        label.textAlignment = .center
        label.numberOfLines = 0
        label.textColor = .secondaryLabel
        return label
    }()
    
    private lazy var refCommandLabel: UILabel = {
        let label = UILabel()
        label.font = UIFont.monospacedSystemFont(ofSize: 14, weight: .medium)
        label.textAlignment = .center
        label.numberOfLines = 0
        label.textColor = .label
        label.backgroundColor = .systemGray6
        label.layer.cornerRadius = 8
        label.layer.masksToBounds = true
        return label
    }()
    
    private lazy var statusCardView: UIView = {
        let view = UIView()
        view.backgroundColor = .systemBackground
        view.layer.cornerRadius = 12
        view.layer.shadowColor = UIColor.black.cgColor
        view.layer.shadowOffset = CGSize(width: 0, height: 2)
        view.layer.shadowRadius = 4
        view.layer.shadowOpacity = 0.1
        view.isHidden = true
        return view
    }()
    
    private lazy var statusTitleLabel: UILabel = {
        let label = UILabel()
        label.text = "Statut de la transaction"
        label.font = UIFont.boldSystemFont(ofSize: 18)
        return label
    }()
    
    private lazy var statusLabel: UILabel = {
        let label = UILabel()
        label.font = UIFont.boldSystemFont(ofSize: 16)
        label.textAlignment = .right
        return label
    }()
    
    private lazy var amountLabel: UILabel = {
        let label = UILabel()
        label.font = UIFont.systemFont(ofSize: 16)
        label.textAlignment = .right
        return label
    }()
    
    private lazy var refreshButton: UIButton = {
        let button = UIButton(type: .system)
        button.setTitle("Actualiser le statut", for: .normal)
        button.titleLabel?.font = UIFont.systemFont(ofSize: 16)
        button.backgroundColor = .systemBlue
        button.setTitleColor(.white, for: .normal)
        button.layer.cornerRadius = 8
        button.addTarget(self, action: #selector(refreshButtonTapped), for: .touchUpInside)
        return button
    }()
    
    private lazy var newPaymentButton: UIButton = {
        let button = UIButton(type: .system)
        button.setTitle("Nouveau paiement", for: .normal)
        button.titleLabel?.font = UIFont.boldSystemFont(ofSize: 18)
        button.backgroundColor = .systemBlue
        button.setTitleColor(.white, for: .normal)
        button.layer.cornerRadius = 12
        button.addTarget(self, action: #selector(newPaymentButtonTapped), for: .touchUpInside)
        return button
    }()
    
    private lazy var homeButton: UIButton = {
        let button = UIButton(type: .system)
        button.setTitle("Retour à l'accueil", for: .normal)
        button.titleLabel?.font = UIFont.systemFont(ofSize: 16)
        button.backgroundColor = .systemGray5
        button.setTitleColor(.label, for: .normal)
        button.layer.cornerRadius = 12
        button.addTarget(self, action: #selector(homeButtonTapped), for: .touchUpInside)
        return button
    }()
    
    // MARK: - Properties
    private let result: PaymentResult
    private let refCommand: String?
    private let returnURL: String?
    private let viewModel = PaymentViewModel()
    private var cancellables = Set<AnyCancellable>()
    
    // MARK: - Initialization
    init(result: PaymentResult, refCommand: String?, returnURL: String? = nil) {
        self.result = result
        self.refCommand = refCommand
        self.returnURL = returnURL
        super.init(nibName: nil, bundle: nil)
    }
    
    required init?(coder: NSCoder) {
        fatalError("init(coder:) has not been implemented")
    }
    
    // MARK: - Lifecycle
    override func viewDidLoad() {
        super.viewDidLoad()
        setupUI()
        setupBindings()
        
        // Vérifier le statut si on a une référence de commande
        if let refCommand = refCommand {
            viewModel.checkTransactionStatus(refCommand)
        }
    }
    
    // MARK: - Setup Methods
    private func setupUI() {
        view.backgroundColor = .systemGroupedBackground
        title = result.title
        
        // Navigation
        navigationItem.hidesBackButton = true
        
        // Configure UI based on result
        iconImageView.image = UIImage(systemName: result.icon)
        titleLabel.text = result.title
        messageLabel.text = result.message
        
        if let refCommand = refCommand {
            refCommandLabel.text = "Référence: \(refCommand)"
            refCommandLabel.isHidden = false
        } else {
            refCommandLabel.isHidden = true
        }
        
        // Add subviews
        view.addSubview(scrollView)
        scrollView.addSubview(contentView)
        
        contentView.addSubview(resultCardView)
        contentView.addSubview(statusCardView)
        contentView.addSubview(newPaymentButton)
        contentView.addSubview(homeButton)
        
        // Result card content
        resultCardView.addSubview(iconImageView)
        resultCardView.addSubview(titleLabel)
        resultCardView.addSubview(messageLabel)
        resultCardView.addSubview(refCommandLabel)
        
        // Status card content
        statusCardView.addSubview(statusTitleLabel)
        statusCardView.addSubview(statusLabel)
        statusCardView.addSubview(amountLabel)
        statusCardView.addSubview(refreshButton)
        
        setupConstraints()
    }
    
    private func setupConstraints() {
        scrollView.snp.makeConstraints { make in
            make.edges.equalTo(view.safeAreaLayoutGuide)
        }
        
        contentView.snp.makeConstraints { make in
            make.edges.equalToSuperview()
            make.width.equalToSuperview()
        }
        
        resultCardView.snp.makeConstraints { make in
            make.top.equalToSuperview().offset(20)
            make.leading.trailing.equalToSuperview().inset(20)
        }
        
        iconImageView.snp.makeConstraints { make in
            make.top.equalToSuperview().offset(30)
            make.centerX.equalToSuperview()
            make.width.height.equalTo(64)
        }
        
        titleLabel.snp.makeConstraints { make in
            make.top.equalTo(iconImageView.snp.bottom).offset(20)
            make.leading.trailing.equalToSuperview().inset(20)
        }
        
        messageLabel.snp.makeConstraints { make in
            make.top.equalTo(titleLabel.snp.bottom).offset(12)
            make.leading.trailing.equalToSuperview().inset(20)
        }
        
        refCommandLabel.snp.makeConstraints { make in
            make.top.equalTo(messageLabel.snp.bottom).offset(20)
            make.leading.trailing.equalToSuperview().inset(20)
            make.height.equalTo(44)
            make.bottom.equalToSuperview().offset(-30)
        }
        
        statusCardView.snp.makeConstraints { make in
            make.top.equalTo(resultCardView.snp.bottom).offset(20)
            make.leading.trailing.equalToSuperview().inset(20)
        }
        
        statusTitleLabel.snp.makeConstraints { make in
            make.top.equalToSuperview().offset(20)
            make.leading.equalToSuperview().offset(16)
        }
        
        statusLabel.snp.makeConstraints { make in
            make.centerY.equalTo(statusTitleLabel)
            make.trailing.equalToSuperview().offset(-16)
        }
        
        amountLabel.snp.makeConstraints { make in
            make.top.equalTo(statusTitleLabel.snp.bottom).offset(16)
            make.leading.trailing.equalToSuperview().inset(16)
        }
        
        refreshButton.snp.makeConstraints { make in
            make.top.equalTo(amountLabel.snp.bottom).offset(20)
            make.leading.trailing.equalToSuperview().inset(16)
            make.height.equalTo(44)
            make.bottom.equalToSuperview().offset(-20)
        }
        
        newPaymentButton.snp.makeConstraints { make in
            make.top.equalTo(statusCardView.snp.bottom).offset(30)
            make.leading.trailing.equalToSuperview().inset(20)
            make.height.equalTo(50)
        }
        
        homeButton.snp.makeConstraints { make in
            make.top.equalTo(newPaymentButton.snp.bottom).offset(16)
            make.leading.trailing.equalToSuperview().inset(20)
            make.height.equalTo(50)
            make.bottom.equalToSuperview().offset(-20)
        }
    }
    
    private func setupBindings() {
        viewModel.$transactionStatus
            .receive(on: DispatchQueue.main)
            .sink { [weak self] status in
                if let status = status {
                    self?.updateTransactionStatus(status)
                }
            }
            .store(in: &cancellables)
        
        viewModel.$isLoading
            .receive(on: DispatchQueue.main)
            .sink { [weak self] isLoading in
                self?.refreshButton.isEnabled = !isLoading
                self?.refreshButton.setTitle(
                    isLoading ? "Vérification..." : "Actualiser le statut",
                    for: .normal
                )
            }
            .store(in: &cancellables)
        
        viewModel.$errorMessage
            .receive(on: DispatchQueue.main)
            .sink { [weak self] errorMessage in
                if let error = errorMessage {
                    self?.showAlert(title: "Erreur", message: error)
                    self?.viewModel.clearMessages()
                }
            }
            .store(in: &cancellables)
    }
    
    // MARK: - Actions
    @objc private func refreshButtonTapped() {
        guard let refCommand = refCommand else { return }
        viewModel.checkTransactionStatus(refCommand)
    }
    
    @objc private func newPaymentButtonTapped() {
        let paymentVC = PaymentViewController()
        let navController = UINavigationController(rootViewController: paymentVC)
        navController.modalPresentationStyle = .fullScreen
        
        present(navController, animated: true)
    }
    
    @objc private func homeButtonTapped() {
        // Retourner à l'écran principal
        if let windowScene = UIApplication.shared.connectedScenes.first as? UIWindowScene,
           let window = windowScene.windows.first {
            let mainVC = ViewController() // Votre ViewController principal
            let navController = UINavigationController(rootViewController: mainVC)
            window.rootViewController = navController
            window.makeKeyAndVisible()
        }
    }
    
    // MARK: - Helper Methods
    private func updateTransactionStatus(_ status: TransactionStatusResponse) {
        statusCardView.isHidden = false
        
        statusLabel.text = "\(status.status.emoji) \(status.status.displayName)"
        statusLabel.textColor = getStatusColor(status.status)
        
        amountLabel.text = "Montant: \(status.amount)"
        
        // Masquer le bouton de refresh si le statut est final
        if [.completed, .failed, .cancelled].contains(status.status) {
            refreshButton.isHidden = true
        }
    }
    
    private func getStatusColor(_ status: TransactionStatus) -> UIColor {
        switch status {
        case .completed: return .systemGreen
        case .pending: return .systemOrange
        case .failed: return .systemRed
        case .cancelled: return .systemGray
        }
    }
    
    private func showAlert(title: String, message: String) {
        let alert = UIAlertController(title: title, message: message, preferredStyle: .alert)
        alert.addAction(UIAlertAction(title: "OK", style: .default))
        present(alert, animated: true)
    }
}
```

## AppDelegate et SceneDelegate

### AppDelegate.swift

```swift
// AppDelegate.swift
import UIKit

@main
class AppDelegate: UIResponder, UIApplicationDelegate {

    func application(_ application: UIApplication, didFinishLaunchingWithOptions launchOptions: [UIApplication.LaunchOptionsKey: Any]?) -> Bool {
        
        // Configuration globale de l'apparence
        configureAppearance()
        
        return true
    }
    
    func application(_ app: UIApplication, open url: URL, options: [UIApplication.OpenURLOptionsKey : Any] = [:]) -> Bool {
        
        // Gestion des URL schemes PayTech
        if url.scheme == "paytech" {
            handlePayTechURL(url)
            return true
        }
        
        return false
    }

    // MARK: UISceneSession Lifecycle
    func application(_ application: UIApplication, configurationForConnecting connectingSceneSession: UISceneSession, options: UIScene.ConnectionOptions) -> UISceneConfiguration {
        return UISceneConfiguration(name: "Default Configuration", sessionRole: connectingSceneSession.role)
    }
    
    // MARK: - Private Methods
    private func configureAppearance() {
        // Configuration de la navigation bar
        let appearance = UINavigationBarAppearance()
        appearance.configureWithOpaqueBackground()
        appearance.backgroundColor = .systemBlue
        appearance.titleTextAttributes = [.foregroundColor: UIColor.white]
        appearance.largeTitleTextAttributes = [.foregroundColor: UIColor.white]
        
        UINavigationBar.appearance().standardAppearance = appearance
        UINavigationBar.appearance().scrollEdgeAppearance = appearance
        UINavigationBar.appearance().tintColor = .white
    }
    
    private func handlePayTechURL(_ url: URL) {
        let urlString = url.absoluteString
        
        if urlString.contains("/success") {
            NotificationCenter.default.post(
                name: .paymentCompleted,
                object: ["url": urlString]
            )
        } else if urlString.contains("/cancel") {
            NotificationCenter.default.post(
                name: .paymentCancelled,
                object: ["url": urlString]
            )
        } else if urlString.contains("/error") {
            NotificationCenter.default.post(
                name: .paymentFailed,
                object: ["url": urlString]
            )
        }
    }
}
```

## Tests

### PaymentViewModelTests.swift

```swift
// PaymentViewModelTests.swift
import XCTest
import Combine
@testable import PayTechApp

class MockPayTechAPIService: PayTechAPIServiceProtocol {
    var shouldSucceed = true
    var mockResponse: PaymentResponse?
    var mockStatus: TransactionStatusResponse?
    
    func requestPayment(_ request: PaymentRequest, completion: @escaping (Result<PaymentResponse, Error>) -> Void) {
        if shouldSucceed, let response = mockResponse {
            completion(.success(response))
        } else {
            completion(.failure(NSError(domain: "Test", code: -1, userInfo: [NSLocalizedDescriptionKey: "Test error"])))
        }
    }
    
    func getTransactionStatus(_ refCommand: String, completion: @escaping (Result<TransactionStatusResponse, Error>) -> Void) {
        if shouldSucceed, let status = mockStatus {
            completion(.success(status))
        } else {
            completion(.failure(NSError(domain: "Test", code: -1, userInfo: [NSLocalizedDescriptionKey: "Test error"])))
        }
    }
    
    func getUserTransactions(_ userId: String, completion: @escaping (Result<[TransactionStatusResponse], Error>) -> Void) {
        completion(.success([]))
    }
}

class PaymentViewModelTests: XCTestCase {
    
    var viewModel: PaymentViewModel!
    var mockAPIService: MockPayTechAPIService!
    var cancellables: Set<AnyCancellable>!
    
    override func setUp() {
        super.setUp()
        mockAPIService = MockPayTechAPIService()
        viewModel = PaymentViewModel(apiService: mockAPIService)
        cancellables = Set<AnyCancellable>()
    }
    
    override func tearDown() {
        viewModel = nil
        mockAPIService = nil
        cancellables = nil
        super.tearDown()
    }
    
    func testRequestPaymentSuccess() {
        // Given
        let expectation = XCTestExpectation(description: "Payment request succeeds")
        
        let mockTransaction = Transaction(
            id: "1",
            refCommand: "CMD_123",
            itemName: "Test Item",
            itemPrice: 1000,
            currency: "XOF",
            paymentMethod: nil,
            status: .pending,
            paytechToken: "token123",
            redirectUrl: "https://paytech.sn/redirect",
            userId: nil,
            completedAt: nil,
            createdAt: "2023-01-01T00:00:00Z",
            updatedAt: "2023-01-01T00:00:00Z"
        )
        
        let mockData = PayTechData(token: "token123", redirectUrl: "https://paytech.sn/redirect")
        mockAPIService.mockResponse = PaymentResponse(success: true, data: mockData, transaction: mockTransaction, error: nil)
        
        // When
        viewModel.$currentTransaction
            .dropFirst()
            .sink { transaction in
                // Then
                XCTAssertNotNil(transaction)
                XCTAssertEqual(transaction?.refCommand, "CMD_123")
                expectation.fulfill()
            }
            .store(in: &cancellables)
        
        viewModel.requestPayment(itemName: "Test Item", itemPrice: 1000)
        
        wait(for: [expectation], timeout: 1.0)
    }
    
    func testRequestPaymentFailure() {
        // Given
        let expectation = XCTestExpectation(description: "Payment request fails")
        mockAPIService.shouldSucceed = false
        
        // When
        viewModel.$errorMessage
            .dropFirst()
            .sink { errorMessage in
                // Then
                XCTAssertNotNil(errorMessage)
                XCTAssertEqual(errorMessage, "Test error")
                expectation.fulfill()
            }
            .store(in: &cancellables)
        
        viewModel.requestPayment(itemName: "Test Item", itemPrice: 1000)
        
        wait(for: [expectation], timeout: 1.0)
    }
    
    func testRequestPaymentValidation() {
        // Given
        let expectation = XCTestExpectation(description: "Payment validation fails")
        
        // When
        viewModel.$errorMessage
            .dropFirst()
            .sink { errorMessage in
                // Then
                XCTAssertNotNil(errorMessage)
                XCTAssertTrue(errorMessage!.contains("champs obligatoires"))
                expectation.fulfill()
            }
            .store(in: &cancellables)
        
        viewModel.requestPayment(itemName: "", itemPrice: 50)
        
        wait(for: [expectation], timeout: 1.0)
    }
}
```

## Bonnes pratiques iOS

### Sécurité

1. **HTTPS obligatoire** avec App Transport Security
2. **Certificate pinning** pour les API critiques
3. **Keychain** pour le stockage sécurisé
4. **URL scheme validation** pour les retours PayTech
5. **Code obfuscation** en production

### Performance

1. **Lazy loading** des vues
2. **Image caching** avec SDWebImage
3. **Memory management** avec weak references
4. **Background processing** avec DispatchQueue
5. **WebView optimization** avec WKWebView

### UX/UI

1. **Human Interface Guidelines** d'Apple
2. **Auto Layout** pour toutes les tailles d'écran
3. **Dark mode** support
4. **Accessibility** avec VoiceOver
5. **Loading states** et error handling

### Architecture

1. **MVVM** avec Combine
2. **Dependency injection** pour les tests
3. **Protocol-oriented programming**
4. **Repository pattern** pour les données
5. **Coordinator pattern** pour la navigation

Cette intégration iOS PayTech offre une solution native complète avec WKWebView sécurisée, architecture MVVM moderne, gestion des états avec Combine, et une expérience utilisateur optimale suivant les guidelines Apple.


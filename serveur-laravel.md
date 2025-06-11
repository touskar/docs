# Intégration Laravel

Ce guide vous explique comment intégrer PayTech dans vos applications Laravel en utilisant les bonnes pratiques du framework.

## Installation

### Via Composer

```bash
composer require guzzlehttp/guzzle
```

### Configuration

Ajoutez vos clés PayTech dans le fichier `.env` :

```env
PAYTECH_API_KEY=votre_api_key
PAYTECH_SECRET_KEY=votre_secret_key
PAYTECH_ENV=test
PAYTECH_IPN_URL=https://votre-site.com/api/paytech/ipn
```

Ajoutez la configuration dans `config/services.php` :

```php
<?php

return [
    // ... autres services
    
    'paytech' => [
        'api_key' => env('PAYTECH_API_KEY'),
        'secret_key' => env('PAYTECH_SECRET_KEY'),
        'env' => env('PAYTECH_ENV', 'test'),
        'base_url' => 'https://paytech.sn/api/payment',
        'ipn_url' => env('PAYTECH_IPN_URL'),
        
        // URLs pour intégrations mobiles (Flutter, React Native)
        'mobile' => [
            'success_url' => 'https://paytech.sn/mobile/success',
            'cancel_url' => 'https://paytech.sn/mobile/cancel',
        ],
        
        // URLs pour intégrations web
        'web' => [
            'success_url' => env('APP_URL') . '/payment/success',
            'cancel_url' => env('APP_URL') . '/payment/cancel',
        ]
    ],
];
```

## Service PayTech

Créez un service pour gérer les interactions avec PayTech :

```php
<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class PayTechService
{
    private $client;
    private $config;

    public function __construct()
    {
        $this->client = new Client();
        $this->config = config('services.paytech');
    }

    /**
     * Créer une demande de paiement
     */
    public function requestPayment(
        string $itemName,
        int $itemPrice,
        string $refCommand,
        array $customField = [],
        ?string $paymentMethod = null,
        ?object $user = null,
        bool $isMobile = false
    ) {
        $url = $this->config['base_url'] . '/request-payment';
        
        // Choisir les URLs selon le type d'intégration
        $urls = $isMobile ? $this->config['mobile'] : $this->config['web'];
        
        $payload = [
            'item_name' => $itemName,
            'item_price' => $itemPrice,
            'currency' => 'xof',
            'ref_command' => $refCommand,
            'command_name' => $itemName,
            'env' => $this->config['env'],
            'success_url' => $urls['success_url'],
            'cancel_url' => $urls['cancel_url'],
            'ipn_url' => $this->config['ipn_url'],
            'custom_field' => json_encode($customField),
        ];
        
        // Ajouter target_payment si spécifié
        if ($paymentMethod) {
            $payload['target_payment'] = $paymentMethod;
        }
        
        $headers = [
            'API_KEY' => $this->config['api_key'],
            'API_SECRET' => $this->config['secret_key'],
            'Content-Type' => 'application/json'
        ];
        
        try {
            $response = $this->client->post($url, [
                'json' => $payload,
                'headers' => $headers
            ]);
            
            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody(), true);
            
            if (in_array($statusCode, [200, 201])) {
                // Ajouter les paramètres de préfillage si utilisateur fourni
                if ($user && $isMobile) {
                    $queryParams = [
                        'pn' => $user->phone_number,
                        'nn' => substr($user->phone_number, 4), // Numéro national
                        'fn' => $user->first_name . ' ' . $user->last_name,
                        'tp' => $paymentMethod,
                        'nac' => 1 // Auto-submit
                    ];
                    
                    $queryString = http_build_query($queryParams);
                    $responseData['redirect_url'] .= '?' . $queryString;
                    $responseData['redirectUrl'] = $responseData['redirect_url'];
                }
                
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }
            
            return [
                'success' => false,
                'errors' => [$statusCode]
            ];
            
        } catch (RequestException $e) {
            Log::error('PayTech API Error', [
                'message' => $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            
            return [
                'success' => false,
                'errors' => [$e->getMessage()]
            ];
        }
    }
    
    /**
     * Valider une notification IPN
     */
    public function validateIPN(array $data): bool
    {
        $receivedApiKeyHash = $data['api_key_sha256'] ?? '';
        $receivedSecretHash = $data['api_secret_sha256'] ?? '';
        
        $expectedApiKeyHash = hash('sha256', $this->config['api_key']);
        $expectedSecretHash = hash('sha256', $this->config['secret_key']);
        
        return $receivedApiKeyHash === $expectedApiKeyHash && 
               $receivedSecretHash === $expectedSecretHash;
    }
    
    /**
     * Valider une signature HMAC
     */
    public function validateHMAC(array $data): bool
    {
        if (!isset($data['hmac_compute'])) {
            return false;
        }
        
        $receivedHmac = $data['hmac_compute'];
        
        // Reconstituer le message pour la vérification HMAC
        $message = $data['amount'] . '|' . $data['id_transaction'] . '|' . $this->config['api_key'];
        $expectedHmac = hash_hmac('sha256', $message, $this->config['secret_key']);
        
        return hash_equals($expectedHmac, $receivedHmac);
    }
}
```

## Modèle Transaction

Créez un modèle pour gérer les transactions :

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_command',
        'token',
        'amount',
        'currency',
        'status',
        'payment_method',
        'custom_field',
        'user_id',
        'paytech_data'
    ];

    protected $casts = [
        'custom_field' => 'array',
        'paytech_data' => 'array',
        'amount' => 'integer'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_FAILED = 'failed';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
    
    public function markAsCompleted()
    {
        $this->update(['status' => self::STATUS_COMPLETED]);
    }
    
    public function markAsCancelled()
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }
}
```

## Migration

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('ref_command')->unique();
            $table->string('token')->nullable();
            $table->integer('amount');
            $table->string('currency', 3)->default('xof');
            $table->enum('status', ['pending', 'completed', 'cancelled', 'failed'])
                  ->default('pending');
            $table->string('payment_method')->nullable();
            $table->json('custom_field')->nullable();
            $table->json('paytech_data')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index('ref_command');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
```

## Contrôleur

```php
<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\PayTechService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private $payTechService;

    public function __construct(PayTechService $payTechService)
    {
        $this->payTechService = $payTechService;
    }

    /**
     * Créer un paiement (Web)
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'item_price' => 'required|integer|min:1',
            'payment_method' => 'nullable|string',
            'custom_field' => 'nullable|array'
        ]);

        $user = Auth::user();
        $refCommand = 'CMD_' . Str::random(10);

        // Créer la transaction en base
        $transaction = Transaction::create([
            'ref_command' => $refCommand,
            'amount' => $request->item_price,
            'payment_method' => $request->payment_method,
            'custom_field' => $request->custom_field ?? [],
            'user_id' => $user->id ?? null,
            'status' => Transaction::STATUS_PENDING
        ]);

        // Appeler PayTech
        $result = $this->payTechService->requestPayment(
            $request->item_name,
            $request->item_price,
            $refCommand,
            $request->custom_field ?? [],
            $request->payment_method,
            $user,
            false // Web integration
        );

        if ($result['success']) {
            // Sauvegarder les données PayTech
            $transaction->update([
                'token' => $result['data']['token'],
                'paytech_data' => $result['data']
            ]);

            return response()->json([
                'success' => true,
                'redirect_url' => $result['data']['redirect_url'],
                'token' => $result['data']['token']
            ]);
        }

        $transaction->markAsFailed();
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création du paiement',
            'errors' => $result['errors']
        ], 400);
    }

    /**
     * Créer un paiement (Mobile/Flutter)
     */
    public function createMobilePayment(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'item_price' => 'required|integer|min:1',
            'payment_method' => 'nullable|string',
            'custom_field' => 'nullable|array',
            'user_phone' => 'required|string',
            'user_name' => 'required|string'
        ]);

        $refCommand = 'MOBILE_' . Str::random(10);

        // Créer un objet utilisateur temporaire pour le préfillage
        $user = (object) [
            'phone_number' => $request->user_phone,
            'first_name' => explode(' ', $request->user_name)[0],
            'last_name' => explode(' ', $request->user_name)[1] ?? ''
        ];

        // Créer la transaction
        $transaction = Transaction::create([
            'ref_command' => $refCommand,
            'amount' => $request->item_price,
            'payment_method' => $request->payment_method,
            'custom_field' => $request->custom_field ?? [],
            'status' => Transaction::STATUS_PENDING
        ]);

        // Appeler PayTech avec intégration mobile
        $result = $this->payTechService->requestPayment(
            $request->item_name,
            $request->item_price,
            $refCommand,
            $request->custom_field ?? [],
            $request->payment_method,
            $user,
            true // Mobile integration
        );

        if ($result['success']) {
            $transaction->update([
                'token' => $result['data']['token'],
                'paytech_data' => $result['data']
            ]);

            return response()->json([
                'success' => true,
                'redirect_url' => $result['data']['redirect_url'],
                'token' => $result['data']['token']
            ]);
        }

        $transaction->markAsFailed();
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création du paiement mobile',
            'errors' => $result['errors']
        ], 400);
    }

    /**
     * Traiter les notifications IPN
     */
    public function handleIPN(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('IPN reçu', $data);

            // Validation de l'IPN (choisir une méthode)
            $isValid = $this->payTechService->validateIPN($data) || 
                      $this->payTechService->validateHMAC($data);

            if (!$isValid) {
                Log::warning('IPN invalide', $data);
                return response('Invalid IPN', 400);
            }

            // Trouver la transaction
            $transaction = Transaction::where('ref_command', $data['ref_command'])->first();

            if (!$transaction) {
                Log::warning('Transaction non trouvée', ['ref_command' => $data['ref_command']]);
                return response('Transaction not found', 404);
            }

            // Traiter selon le type d'événement
            switch ($data['type_event']) {
                case 'sale_complete':
                    $transaction->markAsCompleted();
                    Log::info('Paiement confirmé', ['ref_command' => $data['ref_command']]);
                    break;

                case 'sale_cancel':
                    $transaction->markAsCancelled();
                    Log::info('Paiement annulé', ['ref_command' => $data['ref_command']]);
                    break;

                default:
                    Log::info('Événement non géré', ['type_event' => $data['type_event']]);
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Erreur IPN', [
                'message' => $e->getMessage(),
                'data' => $request->all()
            ]);
            
            return response('Error', 500);
        }
    }

    /**
     * Page de succès
     */
    public function success(Request $request)
    {
        $token = $request->query('token');
        $refCommand = $request->query('ref_command');

        $transaction = Transaction::where('ref_command', $refCommand)->first();

        return view('payment.success', compact('transaction', 'token'));
    }

    /**
     * Page d'annulation
     */
    public function cancel(Request $request)
    {
        $token = $request->query('token');
        $refCommand = $request->query('ref_command');

        $transaction = Transaction::where('ref_command', $refCommand)->first();

        return view('payment.cancel', compact('transaction', 'token'));
    }
}
```

## Routes

```php
<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Routes API
Route::prefix('api')->group(function () {
    Route::post('/payment/create', [PaymentController::class, 'createPayment']);
    Route::post('/payment/mobile/create', [PaymentController::class, 'createMobilePayment']);
    Route::post('/paytech/ipn', [PaymentController::class, 'handleIPN']);
});

// Routes Web
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
```

## Middleware CSRF

Pour l'endpoint IPN, désactivez la vérification CSRF dans `app/Http/Middleware/VerifyCsrfToken.php` :

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'api/paytech/ipn'
    ];
}
```

## Vues Blade

### resources/views/payment/success.blade.php

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4>Paiement réussi !</h4>
                </div>
                <div class="card-body">
                    @if($transaction)
                        <p><strong>Référence :</strong> {{ $transaction->ref_command }}</p>
                        <p><strong>Montant :</strong> {{ number_format($transaction->amount) }} XOF</p>
                        <p><strong>Statut :</strong> {{ ucfirst($transaction->status) }}</p>
                    @endif
                    
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

## Tests

```php
<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Services\PayTechService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_payment()
    {
        $response = $this->postJson('/api/payment/create', [
            'item_name' => 'Test Product',
            'item_price' => 1000,
            'payment_method' => 'Orange Money'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'redirect_url',
                    'token'
                ]);

        $this->assertDatabaseHas('transactions', [
            'amount' => 1000,
            'status' => Transaction::STATUS_PENDING
        ]);
    }

    public function test_can_handle_ipn()
    {
        $transaction = Transaction::factory()->create([
            'ref_command' => 'TEST_001',
            'status' => Transaction::STATUS_PENDING
        ]);

        $ipnData = [
            'type_event' => 'sale_complete',
            'ref_command' => 'TEST_001',
            'api_key_sha256' => hash('sha256', config('services.paytech.api_key')),
            'api_secret_sha256' => hash('sha256', config('services.paytech.secret_key'))
        ];

        $response = $this->post('/api/paytech/ipn', $ipnData);

        $response->assertStatus(200);
        
        $transaction->refresh();
        $this->assertEquals(Transaction::STATUS_COMPLETED, $transaction->status);
    }
}
```

## Commandes Artisan

Créez une commande pour vérifier les transactions en attente :

```php
<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;

class CheckPendingTransactions extends Command
{
    protected $signature = 'paytech:check-pending';
    protected $description = 'Vérifier les transactions en attente';

    public function handle()
    {
        $pendingTransactions = Transaction::pending()
            ->where('created_at', '<', now()->subHours(2))
            ->get();

        foreach ($pendingTransactions as $transaction) {
            $this->info("Transaction en attente: {$transaction->ref_command}");
            // Logique pour vérifier le statut ou marquer comme expirée
        }

        $this->info("Vérification terminée. {$pendingTransactions->count()} transactions trouvées.");
    }
}
```

## Configuration pour la production

### Variables d'environnement

```env
PAYTECH_API_KEY=votre_vraie_api_key
PAYTECH_SECRET_KEY=votre_vraie_secret_key
PAYTECH_ENV=prod
PAYTECH_IPN_URL=https://votre-domaine.com/api/paytech/ipn
```

### Optimisations

```php
// Dans AppServiceProvider
public function boot()
{
    // Cache la configuration PayTech
    if ($this->app->environment('production')) {
        config(['services.paytech' => cache()->remember(
            'paytech.config',
            3600,
            fn() => config('services.paytech')
        )]);
    }
}
```

## Ressources supplémentaires

- [Documentation Laravel](https://laravel.com/docs)
- [Guzzle HTTP Client](https://docs.guzzlephp.org/)
- [Laravel Testing](https://laravel.com/docs/testing)
- [Support PayTech](mailto:support@paytech.sn)


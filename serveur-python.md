# Intégration Python

Ce guide vous explique comment intégrer PayTech dans vos applications Python en utilisant les requêtes HTTP directes.

## Installation des dépendances

```bash
pip install requests
```

## Configuration de base

```python
import requests
import json
import hashlib

# Configuration PayTech
PAYTECH_API_KEY = "votre_api_key"
PAYTECH_SECRET_KEY = "votre_secret_key"
PAYTECH_ENV = "test"  # ou "prod" pour la production
PAYTECH_BASE_URL = "https://paytech.sn/api/payment"
```

## Demande de paiement

### Fonction de base

```python
def request_payment(item_name, item_price, ref_command, custom_field=None):
    """
    Créer une demande de paiement PayTech
    """
    if custom_field is None:
        custom_field = {}
    
    url = f"{PAYTECH_BASE_URL}/request-payment"
    
    payload = {
        'item_name': item_name,
        'item_price': item_price,
        'currency': 'xof',
        'ref_command': ref_command,
        'command_name': item_name,
        'env': PAYTECH_ENV,
        'success_url': "https://votre-site.com/success",
        'cancel_url': "https://votre-site.com/cancel",
        'ipn_url': "https://votre-site.com/ipn",
        'custom_field': json.dumps(custom_field),
    }
    
    headers = {
        'API_KEY': PAYTECH_API_KEY,
        'API_SECRET': PAYTECH_SECRET_KEY,
        'Content-Type': 'application/json'
    }
    
    try:
        response = requests.post(url, json=payload, headers=headers)
        
        if response.status_code in [200, 201]:
            return {
                'success': True,
                'data': response.json()
            }
        else:
            return {
                'success': False,
                'error': f"Erreur HTTP {response.status_code}",
                'details': response.text
            }
            
    except Exception as e:
        return {
            'success': False,
            'error': str(e)
        }
```

### Exemple d'utilisation

```python
# Créer une demande de paiement
result = request_payment(
    item_name="Produit Test",
    item_price=1000,
    ref_command="CMD_001",
    custom_field={"user_id": 123, "order_type": "online"}
)

if result['success']:
    payment_data = result['data']
    redirect_url = payment_data['redirect_url']
    token = payment_data['token']
    
    print(f"Paiement créé avec succès!")
    print(f"Token: {token}")
    print(f"URL de redirection: {redirect_url}")
else:
    print(f"Erreur: {result['error']}")
```

## Validation des notifications IPN

```python
def validate_ipn(request_data):
    """
    Valider une notification IPN PayTech
    """
    # Récupération des hash
    received_api_key_hash = request_data.get('api_key_sha256')
    received_secret_hash = request_data.get('api_secret_sha256')
    
    # Calcul des hash attendus
    expected_api_key_hash = hashlib.sha256(PAYTECH_API_KEY.encode()).hexdigest()
    expected_secret_hash = hashlib.sha256(PAYTECH_SECRET_KEY.encode()).hexdigest()
    
    # Validation
    return (received_api_key_hash == expected_api_key_hash and 
            received_secret_hash == expected_secret_hash)

def process_ipn(request_data):
    """
    Traiter une notification IPN
    """
    if not validate_ipn(request_data):
        return {'error': 'IPN non valide'}
    
    # Traitement selon le type d'événement
    type_event = request_data.get('type_event')
    
    if type_event == 'sale_complete':
        # Paiement réussi
        token = request_data.get('token')
        ref_command = request_data.get('ref_command')
        
        print(f"Paiement réussi pour la commande {ref_command}")
        # Mettre à jour votre base de données
        
    elif type_event == 'sale_cancel':
        # Paiement annulé
        print("Paiement annulé")
        
    return {'status': 'processed'}
```

## Intégration Django

### Views.py

```python
from django.http import JsonResponse, HttpResponse
from django.views.decorators.csrf import csrf_exempt
from django.views.decorators.http import require_http_methods
import json

@csrf_exempt
@require_http_methods(["POST"])
def create_payment(request):
    """
    Vue pour créer un paiement
    """
    try:
        data = json.loads(request.body)
        
        result = request_payment(
            item_name=data['item_name'],
            item_price=data['item_price'],
            ref_command=data['ref_command'],
            custom_field=data.get('custom_field', {})
        )
        
        return JsonResponse(result)
        
    except Exception as e:
        return JsonResponse({
            'success': False,
            'error': str(e)
        }, status=400)

@csrf_exempt
@require_http_methods(["POST"])
def handle_ipn(request):
    """
    Vue pour traiter les notifications IPN
    """
    try:
        # PayTech envoie les données en POST form-encoded
        request_data = request.POST.dict()
        
        result = process_ipn(request_data)
        
        return HttpResponse("OK")
        
    except Exception as e:
        return HttpResponse("ERROR", status=400)
```

### URLs.py

```python
from django.urls import path
from . import views

urlpatterns = [
    path('payment/create/', views.create_payment, name='create_payment'),
    path('payment/ipn/', views.handle_ipn, name='handle_ipn'),
]
```

## Intégration Flask

```python
from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/payment/create', methods=['POST'])
def create_payment():
    """
    Endpoint pour créer un paiement
    """
    try:
        data = request.get_json()
        
        result = request_payment(
            item_name=data['item_name'],
            item_price=data['item_price'],
            ref_command=data['ref_command'],
            custom_field=data.get('custom_field', {})
        )
        
        return jsonify(result)
        
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 400

@app.route('/payment/ipn', methods=['POST'])
def handle_ipn():
    """
    Endpoint pour traiter les notifications IPN
    """
    try:
        # PayTech envoie les données en POST form-encoded
        request_data = request.form.to_dict()
        
        result = process_ipn(request_data)
        
        return "OK"
        
    except Exception as e:
        return "ERROR", 400

if __name__ == '__main__':
    app.run(debug=True)
```

## Gestion des erreurs

```python
def handle_payment_errors(response_data):
    """
    Gérer les erreurs de paiement PayTech
    """
    error_codes = {
        '01': 'Paramètres manquants',
        '02': 'Clés API invalides',
        '03': 'Montant invalide',
        '04': 'Devise non supportée',
        '05': 'Erreur de communication'
    }
    
    if 'error_code' in response_data:
        error_code = response_data['error_code']
        error_message = error_codes.get(error_code, 'Erreur inconnue')
        
        return {
            'success': False,
            'error_code': error_code,
            'error_message': error_message
        }
    
    return {'success': True}
```

## Bonnes pratiques

### Sécurité

- Toujours valider les notifications IPN
- Utiliser HTTPS pour tous les endpoints
- Ne jamais exposer vos clés secrètes côté client
- Implémenter un système de logs pour tracer les transactions

### Performance

- Utiliser des sessions pour éviter les requêtes multiples
- Implémenter un cache pour les réponses fréquentes
- Gérer les timeouts de requêtes

### Tests

```python
import unittest
from unittest.mock import patch, Mock

class TestPayTechIntegration(unittest.TestCase):
    
    def setUp(self):
        self.test_data = {
            'item_name': 'Test Product',
            'item_price': 1000,
            'ref_command': 'TEST_001'
        }
    
    @patch('requests.post')
    def test_successful_payment_request(self, mock_post):
        # Mock de la réponse PayTech
        mock_response = Mock()
        mock_response.status_code = 201
        mock_response.json.return_value = {
            'token': 'test_token',
            'redirect_url': 'https://paytech.sn/payment/test_token'
        }
        mock_post.return_value = mock_response
        
        # Test de la fonction
        result = request_payment(**self.test_data)
        
        self.assertTrue(result['success'])
        self.assertIn('data', result)
    
    def test_ipn_validation(self):
        # Test de validation IPN
        valid_ipn_data = {
            'api_key_sha256': hashlib.sha256(PAYTECH_API_KEY.encode()).hexdigest(),
            'api_secret_sha256': hashlib.sha256(PAYTECH_SECRET_KEY.encode()).hexdigest()
        }
        
        self.assertTrue(validate_ipn(valid_ipn_data))

if __name__ == '__main__':
    unittest.main()
```

## Ressources supplémentaires

- [Documentation API PayTech](/)
- [Exemples de code sur GitHub](https://github.com/paytech-sn)
- [Support technique](mailto:support@paytech.sn)


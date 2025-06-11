# Python - Intégration avancée

Cette section présente l'intégration Python avancée avec PayTech, incluant les fonctionnalités de préfillage des informations utilisateur et la sélection directe des méthodes de paiement.

## 🐍 Fonction de demande de paiement avancée

### Implémentation complète

```python
import requests
import json
import urllib.parse

class PayTechAdvanced:
    
    @staticmethod
    def request_payment(item_name, item_price, ref_command, custom_field, payment_method, user):
        """
        Effectue une demande de paiement avec préfillage des informations utilisateur
        
        Args:
            item_name (str): Nom du produit/service
            item_price (int): Prix en centimes (ex: 1000 pour 10 XOF)
            ref_command (str): Référence unique de la commande
            custom_field (dict): Champs personnalisés (optionnel)
            payment_method (str): Méthode(s) de paiement ciblée(s)
            user (object): Objet utilisateur avec phone_number, first_name, last_name
            
        Returns:
            dict: Réponse avec success (bool) et data/errors
        """
        if custom_field is None:
            custom_field = {}

        url = f'https://paytech.sn/api/payment/request-payment'

        try:
            response = requests.post(
                url,
                json={
                    'item_name': item_name,
                    'item_price': item_price,
                    'currency': 'xof',
                    'ref_command': ref_command,
                    'command_name': item_name,
                    'env': PAYTECH_ENV,  # 'test' ou 'prod'
                    'target_payment': payment_method,
                    'success_url': "https://paytech.sn/mobile/success",
                    'cancel_url': "https://paytech.sn/mobile/cancel",
                    'ipn_url': f"{PAYTECH_IPN}",
                    'custom_field': json.dumps(custom_field),
                },
                headers={
                    'API_KEY': PAYTECH_PUBLIC_KEY,
                    'API_SECRET': PAYTECH_SECRET_KEY,
                }
            )

            json_response = response.json()

            if response.status_code == 201 or response.status_code == 200:
                # Paramètres de préfillage utilisateur
                query_params = {
                    'pn': user.phone_number,  # Numéro complet avec indicatif (+221XXXXXXXX)
                    'nn': user.phone_number[4:],  # Numéro national (7XXXXXXX)
                    'fn': f'{user.first_name} {user.last_name}',  # Nom complet
                    'tp': payment_method,  # Méthode de paiement ciblée
                    'nac': 1  # Auto-submit après préfillage (1=oui, 0=non)
                }

                query_string = urllib.parse.urlencode(query_params)

                # Ajout des paramètres à l'URL de redirection
                json_response['redirectUrl'] = json_response['redirect_url'] = json_response['redirectUrl'] + '?' + query_string
                
                return {
                    'success': True,
                    'data': json_response
                }

            return {
                'success': False,
                "errors": [
                    response.status_code
                ]
            }

        except Exception as err:
            return {
                'success': False,
                "errors": [
                    str(err)
                ]
            }
```

## 🎯 Paramètre target_payment

Le paramètre `target_payment` permet de contrôler quelles méthodes de paiement sont proposées à l'utilisateur.

### Méthodes de paiement disponibles

```python
# Toutes les méthodes disponibles
PAYMENT_METHODS = [
    "Orange Money",
    "PayExpresse", 
    "Carte Bancaire",
    "Wari",
    "Poste Cash",
    "PayPal",
    "Emoney",
    "Tigo Cash",
    "Yup",
    "Joni Joni"
]
```

### Utilisation avec plusieurs méthodes

```python
# Proposer plusieurs méthodes de paiement
payment_method = "Orange Money,PayExpresse,Carte Bancaire"

result = PayTechAdvanced.request_payment(
    item_name="Abonnement Premium",
    item_price=5000,  # 50 XOF
    ref_command="CMD_" + str(int(time.time())),
    custom_field={"user_id": 123, "plan": "premium"},
    payment_method=payment_method,
    user=user_object
)
```

### Utilisation avec une seule méthode (redirection directe)

```python
# Redirection directe vers Orange Money
payment_method = "Orange Money"

result = PayTechAdvanced.request_payment(
    item_name="Recharge téléphone",
    item_price=1000,  # 10 XOF
    ref_command="RECHARGE_" + str(int(time.time())),
    custom_field={"phone": "+221777777777"},
    payment_method=payment_method,
    user=user_object
)

# L'utilisateur sera redirigé directement vers la page Orange Money
```

## 🔧 Paramètres de préfillage

### Structure de l'objet utilisateur

```python
class User:
    def __init__(self, phone_number, first_name, last_name):
        self.phone_number = phone_number  # Format: +221XXXXXXXX
        self.first_name = first_name
        self.last_name = last_name

# Exemple d'utilisation
user = User(
    phone_number="+221777777777",
    first_name="Amadou",
    last_name="Diallo"
)
```

### Paramètres de l'URL de redirection

| Paramètre | Description | Exemple | Obligatoire |
|-----------|-------------|---------|-------------|
| `pn` | Numéro complet avec indicatif pays | `+221777777777` | Non |
| `nn` | Numéro national (sans indicatif) | `777777777` | Non |
| `fn` | Nom complet de l'utilisateur | `Amadou Diallo` | Non |
| `tp` | Méthode de paiement ciblée | `Orange Money` | Non |
| `nac` | Auto-submit après préfillage | `1` (oui) ou `0` (non) | Non |

### Exemple d'URL générée

```
https://paytech.sn/payment/checkout/xxx?pn=%2B221777777777&nn=777777777&fn=Amadou%20Diallo&tp=Orange%20Money&nac=1
```

## 💡 Exemples d'utilisation

### Exemple 1 : Paiement avec choix multiple

```python
import time

# Configuration
PAYTECH_ENV = 'test'  # ou 'prod'
PAYTECH_PUBLIC_KEY = 'votre_api_key'
PAYTECH_SECRET_KEY = 'votre_api_secret'
PAYTECH_IPN = 'https://votre-site.com/ipn'

# Utilisateur
user = User(
    phone_number="+221777777777",
    first_name="Fatou",
    last_name="Sall"
)

# Demande de paiement avec plusieurs options
result = PayTechAdvanced.request_payment(
    item_name="Commande e-commerce",
    item_price=25000,  # 250 XOF
    ref_command=f"ORDER_{int(time.time())}",
    custom_field={
        "order_id": 12345,
        "customer_email": "fatou@example.com"
    },
    payment_method="Orange Money,Carte Bancaire,Wave",
    user=user
)

if result['success']:
    redirect_url = result['data']['redirectUrl']
    print(f"Rediriger vers: {redirect_url}")
else:
    print(f"Erreur: {result['errors']}")
```

### Exemple 2 : Paiement direct Orange Money

```python
# Paiement direct avec Orange Money
result = PayTechAdvanced.request_payment(
    item_name="Recharge mobile",
    item_price=2000,  # 20 XOF
    ref_command=f"RECHARGE_{int(time.time())}",
    custom_field={"type": "recharge", "operator": "orange"},
    payment_method="Orange Money",  # Une seule méthode
    user=user
)

# L'utilisateur ira directement sur la page Orange Money
# avec ses informations pré-remplies
```

### Exemple 3 : Sans auto-submit

```python
# Modifier la fonction pour désactiver l'auto-submit
def request_payment_manual_submit(item_name, item_price, ref_command, custom_field, payment_method, user):
    # ... même code que la fonction principale ...
    
    # Paramètres avec auto-submit désactivé
    query_params = {
        'pn': user.phone_number,
        'nn': user.phone_number[4:],
        'fn': f'{user.first_name} {user.last_name}',
        'tp': payment_method,
        'nac': 0  # L'utilisateur devra cliquer sur "Valider"
    }
    
    # ... reste du code ...
```

## 🔒 Sécurité et bonnes pratiques

### Validation des données

```python
def validate_payment_data(item_price, ref_command, user):
    """Valide les données avant envoi"""
    errors = []
    
    if item_price <= 0:
        errors.append("Le prix doit être supérieur à 0")
    
    if not ref_command or len(ref_command) < 5:
        errors.append("La référence commande doit faire au moins 5 caractères")
    
    if not user.phone_number.startswith('+221'):
        errors.append("Le numéro de téléphone doit commencer par +221")
    
    return errors

# Utilisation
errors = validate_payment_data(item_price, ref_command, user)
if errors:
    print("Erreurs de validation:", errors)
else:
    result = PayTechAdvanced.request_payment(...)
```

### Gestion des erreurs

```python
def handle_payment_response(result):
    """Gère la réponse de la demande de paiement"""
    if result['success']:
        data = result['data']
        
        # Sauvegarder les informations importantes
        payment_info = {
            'token': data.get('token'),
            'redirect_url': data.get('redirectUrl'),
            'ref_command': data.get('ref_command'),
            'created_at': time.time()
        }
        
        # Rediriger l'utilisateur
        return payment_info
    else:
        # Logger l'erreur
        print(f"Erreur PayTech: {result['errors']}")
        return None
```

## 📱 Intégration avec frameworks

### Django

```python
from django.shortcuts import redirect
from django.http import JsonResponse

def initiate_payment(request):
    if request.method == 'POST':
        # Récupérer les données du formulaire
        item_name = request.POST.get('item_name')
        item_price = int(request.POST.get('item_price'))
        payment_method = request.POST.get('payment_method', 'Orange Money')
        
        # Créer l'objet utilisateur
        user = User(
            phone_number=request.user.phone_number,
            first_name=request.user.first_name,
            last_name=request.user.last_name
        )
        
        # Effectuer la demande
        result = PayTechAdvanced.request_payment(
            item_name=item_name,
            item_price=item_price,
            ref_command=f"USER_{request.user.id}_{int(time.time())}",
            custom_field={"user_id": request.user.id},
            payment_method=payment_method,
            user=user
        )
        
        if result['success']:
            return redirect(result['data']['redirectUrl'])
        else:
            return JsonResponse({'error': 'Erreur lors de la demande de paiement'})
```

### Flask

```python
from flask import Flask, request, redirect, jsonify

app = Flask(__name__)

@app.route('/payment', methods=['POST'])
def create_payment():
    data = request.get_json()
    
    user = User(
        phone_number=data['phone_number'],
        first_name=data['first_name'],
        last_name=data['last_name']
    )
    
    result = PayTechAdvanced.request_payment(
        item_name=data['item_name'],
        item_price=data['item_price'],
        ref_command=data['ref_command'],
        custom_field=data.get('custom_field', {}),
        payment_method=data['payment_method'],
        user=user
    )
    
    return jsonify(result)
```

---

> 💡 **Conseil** : Utilisez `target_payment` avec une seule méthode pour une expérience utilisateur plus fluide, et activez `nac=1` pour automatiser le processus de paiement après préfillage des informations.


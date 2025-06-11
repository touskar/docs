# Python - Advanced Integration

This section presents advanced Python integration with PayTech, including user information prefilling features and direct payment method selection.

## 🐍 Advanced Payment Request Function

### Complete Implementation

```python
import requests
import json
import urllib.parse

class PayTechAdvanced:
    
    @staticmethod
    def request_payment(item_name, item_price, ref_command, custom_field, payment_method, user):
        """
        Makes a payment request with user information prefilling
        
        Args:
            item_name (str): Product/service name
            item_price (int): Price in cents (ex: 1000 for 10 XOF)
            ref_command (str): Unique order reference
            custom_field (dict): Custom fields (optional)
            payment_method (str): Target payment method(s)
            user (object): User object with phone_number, first_name, last_name
            
        Returns:
            dict: Response with success (bool) and data/errors
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
                    'env': PAYTECH_ENV,  # 'test' or 'prod'
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
                # User prefilling parameters
                query_params = {
                    'pn': user.phone_number,  # Full number with country code (+221XXXXXXXX)
                    'nn': user.phone_number[4:],  # National number (7XXXXXXX)
                    'fn': f'{user.first_name} {user.last_name}',  # Full name
                    'tp': payment_method,  # Target payment method
                    'nac': 1  # Auto-submit after prefilling (1=yes, 0=no)
                }

                query_string = urllib.parse.urlencode(query_params)

                # Add parameters to redirect URL
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

## 🎯 target_payment Parameter

The `target_payment` parameter allows you to control which payment methods are offered to the user.

### Available Payment Methods

```python
# All available methods
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

### Usage with Multiple Methods

```python
# Offer multiple payment methods
payment_method = "Orange Money,PayExpresse,Carte Bancaire"

result = PayTechAdvanced.request_payment(
    item_name="Premium Subscription",
    item_price=5000,  # 50 XOF
    ref_command="CMD_" + str(int(time.time())),
    custom_field={"user_id": 123, "plan": "premium"},
    payment_method=payment_method,
    user=user_object
)
```

### Usage with Single Method (Direct Redirect)

```python
# Direct redirect to Orange Money
payment_method = "Orange Money"

result = PayTechAdvanced.request_payment(
    item_name="Phone Top-up",
    item_price=1000,  # 10 XOF
    ref_command="TOPUP_" + str(int(time.time())),
    custom_field={"phone": "+221777777777"},
    payment_method=payment_method,
    user=user_object
)

# User will be redirected directly to Orange Money page
```

## 🔧 Prefilling Parameters

### User Object Structure

```python
class User:
    def __init__(self, phone_number, first_name, last_name):
        self.phone_number = phone_number  # Format: +221XXXXXXXX
        self.first_name = first_name
        self.last_name = last_name

# Usage example
user = User(
    phone_number="+221777777777",
    first_name="Amadou",
    last_name="Diallo"
)
```

### Redirect URL Parameters

| Parameter | Description | Example | Required |
|-----------|-------------|---------|----------|
| `pn` | Full number with country code | `+221777777777` | No |
| `nn` | National number (without country code) | `777777777` | No |
| `fn` | User's full name | `Amadou Diallo` | No |
| `tp` | Target payment method | `Orange Money` | No |
| `nac` | Auto-submit after prefilling | `1` (yes) or `0` (no) | No |

### Generated URL Example

```
https://paytech.sn/payment/checkout/xxx?pn=%2B221777777777&nn=777777777&fn=Amadou%20Diallo&tp=Orange%20Money&nac=1
```

## 💡 Usage Examples

### Example 1: Payment with Multiple Choices

```python
import time

# Configuration
PAYTECH_ENV = 'test'  # or 'prod'
PAYTECH_PUBLIC_KEY = 'your_api_key'
PAYTECH_SECRET_KEY = 'your_api_secret'
PAYTECH_IPN = 'https://your-site.com/ipn'

# User
user = User(
    phone_number="+221777777777",
    first_name="Fatou",
    last_name="Sall"
)

# Payment request with multiple options
result = PayTechAdvanced.request_payment(
    item_name="E-commerce Order",
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
    print(f"Redirect to: {redirect_url}")
else:
    print(f"Error: {result['errors']}")
```

### Example 2: Direct Orange Money Payment

```python
# Direct payment with Orange Money
result = PayTechAdvanced.request_payment(
    item_name="Mobile Top-up",
    item_price=2000,  # 20 XOF
    ref_command=f"TOPUP_{int(time.time())}",
    custom_field={"type": "topup", "operator": "orange"},
    payment_method="Orange Money",  # Single method
    user=user
)

# User will go directly to Orange Money page
# with their information pre-filled
```

### Example 3: Without Auto-submit

```python
# Modify function to disable auto-submit
def request_payment_manual_submit(item_name, item_price, ref_command, custom_field, payment_method, user):
    # ... same code as main function ...
    
    # Parameters with auto-submit disabled
    query_params = {
        'pn': user.phone_number,
        'nn': user.phone_number[4:],
        'fn': f'{user.first_name} {user.last_name}',
        'tp': payment_method,
        'nac': 0  # User will need to click "Submit"
    }
    
    # ... rest of code ...
```

## 🔒 Security and Best Practices

### Data Validation

```python
def validate_payment_data(item_price, ref_command, user):
    """Validates data before sending"""
    errors = []
    
    if item_price <= 0:
        errors.append("Price must be greater than 0")
    
    if not ref_command or len(ref_command) < 5:
        errors.append("Order reference must be at least 5 characters")
    
    if not user.phone_number.startswith('+221'):
        errors.append("Phone number must start with +221")
    
    return errors

# Usage
errors = validate_payment_data(item_price, ref_command, user)
if errors:
    print("Validation errors:", errors)
else:
    result = PayTechAdvanced.request_payment(...)
```

### Error Handling

```python
def handle_payment_response(result):
    """Handles payment request response"""
    if result['success']:
        data = result['data']
        
        # Save important information
        payment_info = {
            'token': data.get('token'),
            'redirect_url': data.get('redirectUrl'),
            'ref_command': data.get('ref_command'),
            'created_at': time.time()
        }
        
        # Redirect user
        return payment_info
    else:
        # Log error
        print(f"PayTech Error: {result['errors']}")
        return None
```

## 📱 Framework Integration

### Django

```python
from django.shortcuts import redirect
from django.http import JsonResponse

def initiate_payment(request):
    if request.method == 'POST':
        # Get form data
        item_name = request.POST.get('item_name')
        item_price = int(request.POST.get('item_price'))
        payment_method = request.POST.get('payment_method', 'Orange Money')
        
        # Create user object
        user = User(
            phone_number=request.user.phone_number,
            first_name=request.user.first_name,
            last_name=request.user.last_name
        )
        
        # Make request
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
            return JsonResponse({'error': 'Payment request error'})
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

> 💡 **Tip**: Use `target_payment` with a single method for a smoother user experience, and enable `nac=1` to automate the payment process after prefilling information.


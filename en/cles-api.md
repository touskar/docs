# PayTech API Keys

API keys are essential for authenticating your requests to PayTech servers. This section explains how to obtain, configure, and use your API keys securely.

## What is an API Key?

An Application Programming Interface (API) key is a unique code used by computer programs to authenticate with the PayTech API. Each merchant account has two keys:

- **API_KEY**: Public identification key
- **API_SECRET**: Secret key for request signing

## Obtaining API Keys

### Step 1: Dashboard Access

1. Connect to your PayTech client space: [paytech.sn/app](https://paytech.sn/app)
2. Use your login credentials (email and password)
3. Access the main dashboard

### Step 2: Navigate to API Settings

1. In the main menu, click on "**Settings**"
2. Select "**API**" from the submenu
3. You will access the API key management page

### Step 3: Retrieve Keys

On the API page, you will find:

- **API Key**: Your public identification key (masked by default)
- **API Secret**: Your secret key (masked by default)
- "**Regenerate**" button: To create new keys if necessary

⚠️ **Important**: Note your keys in a secure location. The secret key will only be displayed once for security reasons.

## Key Configuration

### Environment Variables (Recommended)

The most secure method is to store your keys in environment variables:

```bash
# .env file
PAYTECH_API_KEY=1afac8504fa5ec74e3e3734c3829793eb6bd5f4602c84ac4a50693698129150e
PAYTECH_API_SECRET=96bc36c11560f2151c4b43eee310cefabc2e9e9000f7e315c3ca3d279e3f98ac
PAYTECH_ENV=test  # or 'prod' for production
```

### PHP Configuration

```php
<?php
// PayTech configuration
define('PAYTECH_API_KEY', getenv('PAYTECH_API_KEY'));
define('PAYTECH_API_SECRET', getenv('PAYTECH_API_SECRET'));
define('PAYTECH_ENV', getenv('PAYTECH_ENV') ?: 'test');

// Base URLs
define('PAYTECH_API_URL', 'https://paytech.sn/api');
?>
```

### Node.js Configuration

```javascript
// config/paytech.js
module.exports = {
    apiKey: process.env.PAYTECH_API_KEY,
    apiSecret: process.env.PAYTECH_API_SECRET,
    environment: process.env.PAYTECH_ENV || 'test',
    baseUrl: 'https://paytech.sn/api'
};
```

### Python Configuration

```python
# config/paytech.py
import os

PAYTECH_API_KEY = os.getenv('PAYTECH_API_KEY')
PAYTECH_API_SECRET = os.getenv('PAYTECH_API_SECRET')
PAYTECH_ENV = os.getenv('PAYTECH_ENV', 'test')
PAYTECH_API_URL = 'https://paytech.sn/api'
```

## Using Keys in Requests

### Required HTTP Headers

All requests to the PayTech API must include these headers:

```http
Content-Type: application/json
API-KEY: your_api_key_here
API-SECRET: your_api_secret_here
```

### Example with cURL

```bash
curl -X POST https://paytech.sn/api/payment/request-payment \
  -H "Content-Type: application/json" \
  -H "API-KEY: 1afac8504fa5ec74e3e3734c3829793eb6bd5f4602c84ac4a50693698129150e" \
  -H "API-SECRET: 96bc36c11560f2151c4b43eee310cefabc2e9e9000f7e315c3ca3d279e3f98ac" \
  -d '{
    "item_name": "iPhone 13 Pro",
    "item_price": 650000,
    "currency": "XOF",
    "ref_command": "CMD_001",
    "command_name": "iPhone Purchase",
    "env": "test"
  }'
```

---

> 🔒 **Security Note**: Your API secret is extremely sensitive. Never share it publicly or include it in client-side code. Always validate IPN signatures to ensure notification authenticity.


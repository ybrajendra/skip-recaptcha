# CloudCommerce SkipRecaptcha Module

## Overview

The SkipRecaptcha module allows administrators to bypass reCAPTCHA validation for specific whitelisted IP addresses in Magento 2. This is particularly useful for development environments, automated testing, or trusted internal networks where reCAPTCHA verification is not necessary.

## Features

- Enable/disable module functionality via admin configuration
- Whitelist specific IP addresses to skip reCAPTCHA validation
- Supports both direct IP addresses and forwarded IPs (X-Forwarded-For header)
- Admin panel configuration interface

## Installation

### Via Composer (Recommended)
```bash
composer require cloudcommerce/skip-recaptcha
php bin/magento module:enable CloudCommerce_SkipRecaptcha
php bin/magento setup:upgrade
php bin/magento cache:flush
```

### Manual Installation
1. Copy the module files to `app/code/CloudCommerce/SkipRecaptcha/`
2. Run the following commands:
   ```bash
   php bin/magento module:enable CloudCommerce_SkipRecaptcha
   php bin/magento setup:upgrade
   php bin/magento cache:flush
   ```

## Configuration

1. Navigate to **Stores > Configuration > General > Skip Admin Recaptcha**
2. Set **Enable Module** to "Yes"
3. Enter IP addresses in **Whitelisted IPs** field (comma-separated)
4. Save configuration

## Usage

Once configured, users accessing Magento from whitelisted IP addresses will automatically bypass reCAPTCHA validation on admin login and other protected forms.

### How It Works
The module uses a plugin to intercept the `isCaptchaEnabledFor` method and returns `false` when the current IP address is in the whitelist, effectively disabling reCAPTCHA validation.

## Version
1.0.0

## Author
CloudCommerce
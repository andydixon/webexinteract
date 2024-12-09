# WebexInteract PHP Library

[![License: GPL-3.0-or-later](https://img.shields.io/badge/License-GPL--3.0--or--later-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

An unofficial PHP library for interacting with the WebEx Interact API.  
This library allows you to schedule and send SMS messages via WebEx Interact endpoints.

## Features

- Send SMS messages to one or more recipients.
- Schedule messages to be sent at a future date/time.
- Set message expiration times.

## Requirements

- PHP >= 7.4
- Composer
- `guzzlehttp/guzzle` >= 7.0
- `ext-json`

## Installation

Install the library via Composer:

```bash
composer require andydixon/webex-interact
```

## Example

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use andydixon\webexinteract\Sms\InteractSms;

// Create instance
$interact = InteractSms::sms_api("your-api-key")
    ->setOriginator("YourBrand")
    ->addRecipient("+1234567890")
    ->message("Hello, this is a test message!");

// Optionally set scheduled time and expiry
// $interact->sendAt((new DateTime('now', new DateTimeZone('UTC')))->add(new DateInterval('PT10M')));
// $interact->expires((new DateTime('now', new DateTimeZone('UTC')))->add(new DateInterval('P1D')));

// Send
try {
    $response = $interact->sendSms();
    echo "Status: " . $response->getStatus() . PHP_EOL;
    echo "Body: " . $response->getResponseBody() . PHP_EOL;
} catch (\andydixon\webexinteract\Sms\InteractError $err) {
    echo "Error: " . $err->getMessage() . PHP_EOL;
    if ($err->getData()) {
        echo "Data: " . $err->getData() . PHP_EOL;
    }
}

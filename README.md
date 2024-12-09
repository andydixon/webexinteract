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
require __DIR__ . '/vendor/autoload.php';

use AndyDixon\WebexInteract\Sms\InteractSms;

$interact = InteractSms::sms_api("your-api-key")
    ->setOriginator("YourBrand")
    ->addRecipient("+1234567890")
    ->message("Hello, this is a test message!");

try {
    $response = $interact->sendSms();
    if ($response->hasErrors()) {
        foreach ($response->getErrors() as $errorObject) {
            echo "Error Field: " . $errorObject->getField() . "\n";
            echo "Error Message: " . $errorObject->getMessage() . "\n";
            echo "Error Code: " . $errorObject->getCode() . "\n";
        }
    } else {
        echo "Request ID: " . $response->getRequestId() . "\n";
        foreach ($response->getMessages() as $message) {
            echo "Transaction ID: " . $message->getTransactionId() . "\n";
            echo "To: " . $message->getTo() . "\n";
            echo "Status: " . $message->getStatus() . "\n";
            echo "Code: " . $message->getCode() . "\n";
        }
    }
} catch (\AndyDixon\WebexInteract\Sms\InteractError $err) {
    echo "Error: " . $err->getMessage() . "\n";
    if ($err->getData()) {
        echo "Response Data: " . $err->getData() . "\n";
    }
}

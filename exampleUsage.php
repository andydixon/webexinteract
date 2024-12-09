<?php
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
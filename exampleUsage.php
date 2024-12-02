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

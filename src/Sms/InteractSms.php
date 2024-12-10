<?php

namespace andydixon\webexinteract\Sms;

use GuzzleHttp\Client;
use DateTime;
use DateTimeZone;
use DateInterval;
use Exception;

/**
 * InteractSms class
 * @author Andy Dixon <andy@andydixon.com>
 * @license GPL3
 */

class InteractSms
{
    private string $apiEndpoint;
    private string $apiKey;
    private string $body;
    private string $from;
    /**
     * @var string[]
     */
    private array $to;
    private ?DateTime $scheduleTime;
    private ?DateTime $validUntil;

    public function __construct(string $apiKey)
    {
        $this->apiEndpoint = "https://api.webexinteract.com/v1/sms";
        $this->apiKey = $apiKey;
        $this->body = "";
        $this->from = "";
        $this->to = [];
        $this->scheduleTime = null;
        $this->validUntil = null;
    }

    public static function sms_api(string $apiKey): self
    {
        return new self($apiKey);
    }

    /**
     * Add a recipient to receive an SMS
     * @param string $recipient
     * @return $this
     */
    public function addRecipient(string $recipient): self
    {
        $this->to[] = $recipient;
        return $this;
    }

    /**
     * Set the message to be sent to the recipients
     * @param string $message_body
     * @return $this
     */
    public function message(string $message_body): self
    {
        $this->body = $message_body;
        return $this;
    }

    /**
     * Set the originator of the message (the Sender Name)
     * @param string $sender_name
     * @return $this
     */
    public function setOriginator(string $sender_name): self
    {
        $this->from = $sender_name;
        return $this;
    }

    /**
     * Set a timestamp of when the message expires (will not be delivered to the handset after this timestamp)
     * @param DateTime $expiry_timestamp
     * @return $this
     */
    public function expires(DateTime $expiry_timestamp): self
    {
        $this->validUntil = $expiry_timestamp;
        return $this;
    }

    /**
     * Specify the timestamp that the message should be sent to the recipients
     * @param DateTime $send_timestamp
     * @return $this
     */
    public function sendAt(DateTime $send_timestamp): self
    {
        $this->scheduleTime = $send_timestamp;
        return $this;
    }

    /**
     * Send the SMS message to WebEx Interact for processing
     * @throws InteractError
     */
    public function sendSms(): InteractResponse
    {
        if ($this->scheduleTime === null) {
            $this->scheduleTime = new DateTime("now", new DateTimeZone("UTC"));
            $this->scheduleTime->add(new DateInterval("PT5M"));
        }

        if ($this->validUntil === null) {
            $this->validUntil = new DateTime("now", new DateTimeZone("UTC"));
            $this->validUntil->add(new DateInterval("P2D"));
        }

        $recipients = [new Phonenumber($this->to)];

        $schedule_time_str = $this->scheduleTime->format("Y-m-d\TH:i:s\Z");
        $validity_str = $this->validUntil->format("Y-m-d\TH:i:s\Z");

        $data = new Payload(
            $this->body,
            $this->from,
            $schedule_time_str,
            $validity_str,
            $recipients
        );

        $payload = json_encode($data->toArray());
        if ($payload === false) {
            throw new InteractError("Failed to serialise payload");
        }

        $client = new Client([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-auth-key' => $this->apiKey,
                'Content-Length' => strlen($payload),
                'User-Agent' => 'AndyDixon/InteractSms',
            ]
        ]);

        try {
            $response = $client->post($this->apiEndpoint, ['body' => $payload]);
            $statusCode = $response->getStatusCode();
            $responseBody = (string)$response->getBody();
            $decoded = json_decode($responseBody, true);

            if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new InteractError("Invalid JSON in response", $responseBody);
            }

            // Extract errors if present
            $errors = [];
            if (isset($decoded['errors']) && is_array($decoded['errors'])) {
                foreach ($decoded['errors'] as $err) {
                    $errors[] = new InteractErrorObject(
                        $err['field'] ?? null,
                        $err['message'] ?? 'Unknown error message',
                        isset($err['code']) ? (int)$err['code'] : null
                    );
                }
            }

            // Extract messages if present
            $messages = [];
            if (isset($decoded['messages']) && is_array($decoded['messages'])) {
                foreach ($decoded['messages'] as $msg) {
                    $messages[] = new InteractMessage(
                        $msg['transaction_id'] ?? '',
                        $msg['to'] ?? '',
                        $msg['status'] ?? '',
                        isset($msg['code']) ? (int)$msg['code'] : 0
                    );
                }
            }

            $requestId = $decoded['request_id'] ?? null;

            // If non-2xx, treat as error
            if ($statusCode < 200 || $statusCode > 299) {
                // Return a response object so you can interrogate
                $resp = new InteractResponse($statusCode, $responseBody, $requestId, $messages, $errors);
                if (!empty($errors)) {
                    // Throw exception with first error message or combined
                    $errorMessages = array_map(fn($e) => $e->getMessage(), $errors);
                    throw new InteractError("API returned error ($statusCode): " . implode("; ", $errorMessages), $responseBody);
                } else {
                    throw new InteractError("API returned HTTP status $statusCode without structured errors", $responseBody);
                }
            }

            // 2xx status code
            if (!empty($errors)) {
                // Even though 2xx, errors present
                $resp = new InteractResponse($statusCode, $responseBody, $requestId, $messages, $errors);
                $errorMessages = array_map(fn($e) => $e->getMessage(), $errors);
                throw new InteractError("API returned success status but with errors: " . implode("; ", $errorMessages), $responseBody);
            }

            // Successful scenario, return structured response
            return new InteractResponse($statusCode, $responseBody, $requestId, $messages, $errors);

        } catch (Exception $e) {
            throw new InteractError("Fatal Error: " . $e->getMessage());
        }
    }
}

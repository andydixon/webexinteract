<?php

namespace andydixon\webexinteract\Sms;

use GuzzleHttp\Client;
use DateTime;
use DateTimeZone;
use DateInterval;
use Exception;

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

    public function addRecipient(string $recipient): self
    {
        $this->to[] = $recipient;
        return $this;
    }

    public function message(string $message_body): self
    {
        $this->body = $message_body;
        return $this;
    }

    public function setOriginator(string $sender_name): self
    {
        $this->from = $sender_name;
        return $this;
    }

    public function expires(DateTime $expiry_timestamp): self
    {
        $this->validUntil = $expiry_timestamp;
        return $this;
    }

    public function sendAt(DateTime $send_timestamp): self
    {
        $this->scheduleTime = $send_timestamp;
        return $this;
    }

    /**
     * @throws InteractError
     */
    public function sendSms(): InteractResponse
    {
        // If schedule_time is not set, default to now +5 minutes
        if ($this->scheduleTime === null) {
            $this->scheduleTime = new DateTime("now", new DateTimeZone("UTC"));
            $this->scheduleTime->add(new DateInterval("PT5M"));
        }

        // If valid_until is not set, default to now +2 days
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
            throw new InteractError("Failed to serialize payload");
        }

        $client = new Client([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-auth-key' => $this->apiKey,
                'Content-Length' => strlen($payload),
                'User-Agent' => 'PHP/InteractSms',
            ]
        ]);

        try {
            $response = $client->post($this->apiEndpoint, ['body' => $payload]);
            $statusCode = $response->getStatusCode();
            $responseBody = (string)$response->getBody();

            if ($statusCode < 200 || $statusCode > 299) {
                // Non-2xx response
                throw new InteractError("API returned error $statusCode", $responseBody);
            }

            return new InteractResponse($statusCode, $responseBody);
        } catch (Exception $e) {
            throw new InteractError("Fatal Error: " . $e->getMessage());
        }
    }
}

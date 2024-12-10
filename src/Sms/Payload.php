<?php

namespace andydixon\webexinteract\Sms;

/**
 * Interact API Payload Object class
 * @author Andy Dixon <andy@andydixon.com>
 * @license GPL3
 */

class Payload
{
    private string $message_body;
    private string $from_field;
    private string $send_at;
    private string $valid_until;
    /**
     * @var Phonenumber[]
     */
    private array $to_field;

    /**
     * @param Phonenumber[] $to_field
     */
    public function __construct(string $message_body, string $from_field, string $send_at, string $valid_until, array $to_field)
    {
        $this->message_body = $message_body;
        $this->from_field = $from_field;
        $this->send_at = $send_at;
        $this->valid_until = $valid_until;
        $this->to_field = $to_field;
    }

    public function toArray(): array
    {
        return [
            'message_body' => $this->message_body,
            'from' => $this->from_field,
            'send_at' => $this->send_at,
            'valid_until' => $this->valid_until,
            'to' => array_map(fn($ph) => $ph->toArray(), $this->to_field),
        ];
    }
}

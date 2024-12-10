<?php

namespace andydixon\webexinteract\Sms;

/**
 * InteractMessage Object class
 * @author Andy Dixon <andy@andydixon.com>
 * @license GPL3
 */

class InteractMessage
{
    private string $transactionId;
    private string $to;
    private string $status;
    private int $code;

    public function __construct(string $transactionId, string $to, string $status, int $code)
    {
        $this->transactionId = $transactionId;
        $this->to = $to;
        $this->status = $status;
        $this->code = $code;
    }

    /**
     * Return the transaction ID (May be requested for support purposes)
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * Get the recipients of the message
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * Get the status of the transaction
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCode(): int
    {
        return $this->code;
    }
}

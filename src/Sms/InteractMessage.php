<?php

namespace andydixon\webexinteract\Sms;

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

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCode(): int
    {
        return $this->code;
    }
}

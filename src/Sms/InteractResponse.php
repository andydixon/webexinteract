<?php

namespace andydixon\webexinteract\Sms;

class InteractResponse {
    private int $status;
    private string $responseBody;

    public function __construct(int $status, string $responseBody)
    {
        $this->status = $status;
        $this->responseBody = $responseBody;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getResponseBody(): string
    {
        return $this->responseBody;
    }
}

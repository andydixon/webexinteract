<?php

namespace andydixon\webexinteract\Sms;

class InteractResponse
{
    private int $status;
    private string $rawResponse;
    private ?string $requestId = null;
    /** @var InteractMessage[] */
    private array $messages = [];
    /** @var InteractErrorObject[] */
    private array $errors = [];

    public function __construct(
        int $status,
        string $rawResponse,
        ?string $requestId = null,
        array $messages = [],
        array $errors = []
    ) {
        $this->status = $status;
        $this->rawResponse = $rawResponse;
        $this->requestId = $requestId;
        $this->messages = $messages;
        $this->errors = $errors;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getRawResponse(): string
    {
        return $this->rawResponse;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    /**
     * @return InteractMessage[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return InteractErrorObject[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }
}

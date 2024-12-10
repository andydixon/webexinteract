<?php

namespace andydixon\webexinteract\Sms;

/**
 * InteractResponse Object class
 * @author Andy Dixon <andy@andydixon.com>
 * @license GPL3
 */

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

    /**
     * Get the status code from Interact
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Get the raw response in JSON format
     * @return string
     */
    public function getRawResponse(): string
    {
        return $this->rawResponse;
    }

    /**
     * Get the request ID
     * @return string|null
     */
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
     * Return any errors
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

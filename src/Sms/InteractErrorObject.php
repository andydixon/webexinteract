<?php

namespace andydixon\webexinteract\Sms;
/**
 * InteractErrorObject class
 * @author Andy Dixon <andy@andydixon.com>
 * @license GPL3
 */
class InteractErrorObject
{
    private ?string $field;
    private string $message;
    private ?int $code;

    public function __construct(?string $field, string $message, ?int $code)
    {
        $this->field = $field;
        $this->message = $message;
        $this->code = $code;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }
}

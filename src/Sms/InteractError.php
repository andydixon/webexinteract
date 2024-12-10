<?php
namespace andydixon\webexinteract\Sms;
use Exception;

/**
 * InteractError class
 * @author Andy Dixon <andy@andydixon.com>
 * @license GPL3
 */



class InteractError extends Exception
{
    private ?string $data;

    public function __construct(string $message, ?string $data = null)
    {
        parent::__construct($message);
        $this->data = $data;
    }

    public function getData(): ?string
    {
        return $this->data;
    }
}

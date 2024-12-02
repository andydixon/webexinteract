<?php

namespace andydixon\webexinteract\Sms;

class Phonenumber
{
    /**
     * @var string[]
     */
    private array $phone;

    /**
     * @param string[] $phone
     */
    public function __construct(array $phone)
    {
        $this->phone = $phone;
    }

    public function toArray(): array
    {
        return ['phone' => $this->phone];
    }
}

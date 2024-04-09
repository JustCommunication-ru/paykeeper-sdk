<?php

namespace JustCommunication\PaykeeperSDK\TokenHandler;

class InMemoryTokenHandler extends AbstractTokenHandler
{
    public function __construct(string $token = '')
    {
        $this->token = $token;
    }
}

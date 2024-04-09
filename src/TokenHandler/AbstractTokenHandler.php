<?php

namespace JustCommunication\PaykeeperSDK\TokenHandler;

abstract class AbstractTokenHandler implements TokenHandlerInterface
{
    protected string $token;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}

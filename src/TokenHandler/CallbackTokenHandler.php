<?php

namespace JustCommunication\PaykeeperSDK\TokenHandler;

class CallbackTokenHandler extends AbstractTokenHandler
{
    protected $setAccessTokenCallback;

    public function __construct(callable $getTokenCallback, callable $setTokenCallback)
    {
        $this->token = call_user_func($getTokenCallback);
        $this->setAccessTokenCallback = $setTokenCallback;
    }

    public function setToken(string $token): void
    {
        call_user_func($this->setAccessTokenCallback, $token);
        parent::setToken($token);
    }
}

<?php

namespace JustCommunication\PaykeeperSDK\TokenHandler;

interface TokenHandlerInterface
{
    public function getToken(): string;
    public function setToken(string $access_token): void;
}

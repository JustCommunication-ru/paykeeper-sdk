<?php

namespace JustCommunication\PaykeeperSDK\TokenHandler;

class FileTokenHandler extends AbstractTokenHandler
{
    protected string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->token = (file_exists($this->filename) ? file_get_contents($this->filename) : '');
    }

    public function setToken(string $token): void
    {
        file_put_contents($this->filename, $token);
        parent::setToken($token);
    }
}

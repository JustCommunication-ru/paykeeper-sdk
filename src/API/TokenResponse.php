<?php

namespace JustCommunication\PaykeeperSDK\API;

use JustCommunication\PaykeeperSDK\Exception\PaykeeperAPIException;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

class TokenResponse extends AbstractResponse
{
    protected string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public static function createFromResponse(HttpResponseInterface $response): ResponseInterface
    {
        $data = self::extractData($response);
        if (!isset($data['token'])) {
            throw new PaykeeperAPIException('Missing `token` from response');
        }

        return new self($data['token']);
    }
}

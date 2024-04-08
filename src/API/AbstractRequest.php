<?php

namespace JustCommunication\PaykeeperSDK\API;

abstract class AbstractRequest implements RequestInterface
{
    public const URI = null;
    public const HTTP_METHOD = 'GET';
    public const RESPONSE_CLASS = null;

    public function getUri(): string
    {
        return $this::URI;
    }

    public function getHttpMethod(): string
    {
        return $this::HTTP_METHOD;
    }

    public function getResponseClass(): string
    {
        return $this::RESPONSE_CLASS;
    }

    public function createHttpClientParams(): array
    {
        return [];
    }
}

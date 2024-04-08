<?php

namespace JustCommunication\PaykeeperSDK\API;

interface RequestInterface
{
    public function getUri(): string;
    public function getHttpMethod(): string;
    public function createHttpClientParams(): array;
    public function getResponseClass(): string;
}

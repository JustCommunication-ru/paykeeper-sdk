<?php

namespace JustCommunication\PaykeeperSDK\API;

use JustCommunication\PaykeeperSDK\Exception\PaykeeperAPIException;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

interface ResponseInterface
{
    /**
     * @throws PaykeeperAPIException
     */
    public static function createFromResponse(HttpResponseInterface $response): ResponseInterface;
}

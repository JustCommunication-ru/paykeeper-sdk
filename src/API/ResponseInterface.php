<?php

namespace JustCommunication\PaykeeperSDK\API;

use JustCommunication\JCBankSDK\Exception\JCBankAPIException;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

interface ResponseInterface
{
    /**
     * @throws JCBankAPIException
     */
    public static function createFromResponse(HttpResponseInterface $response): ResponseInterface;
}

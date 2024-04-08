<?php

namespace JustCommunication\PaykeeperSDK\API\Systems;

use JustCommunication\PaykeeperSDK\API\AbstractResponse;
use JustCommunication\PaykeeperSDK\API\ResponseInterface;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

class SystemsListResponse extends AbstractResponse
{
    public static function createFromResponse(HttpResponseInterface $response): ResponseInterface
    {
        $data = self::extractData($response);
        print_r($data);
        exit;
    }
}

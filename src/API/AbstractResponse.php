<?php

namespace JustCommunication\PaykeeperSDK\API;

use JustCommunication\PaykeeperSDK\Exception\PaykeeperAPIException;
use Psr\Http\Message\ResponseInterface as HttpResponseInterfaceAlias;

abstract class AbstractResponse implements ResponseInterface
{
    /**
     * @throws PaykeeperAPIException
     */
    public static function extractData(HttpResponseInterfaceAlias $response): array
    {
        $response_string = (string)$response->getBody();
        $response_data = json_decode($response_string, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new PaykeeperAPIException('Unable to decode response data. Error: ' . json_last_error_msg());
        }

        if (isset($response_data['result']) && $response_data['result'] === 'fail') {
            throw new PaykeeperAPIException($response_data['msg']);
        }

        return $response_data;
    }
}

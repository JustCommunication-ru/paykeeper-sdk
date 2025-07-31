<?php

namespace JustCommunication\PaykeeperSDK\API\Invoice;

use JustCommunication\PaykeeperSDK\API\AbstractResponse;
use JustCommunication\PaykeeperSDK\API\ResponseInterface;
use JustCommunication\PaykeeperSDK\Exception\PaykeeperAPIException;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

class InvoiceRevokeResponse extends AbstractResponse
{
    public static function createFromResponse(HttpResponseInterface $response): ResponseInterface
    {
        $data = self::extractData($response);
        if (!isset($data['result'])) {
            throw new PaykeeperAPIException('Missing `result` from response');
        }

        if ($data['result'] !== 'success') {
            throw new PaykeeperAPIException('Response `result` is not "success", but "' . $data['result'] . '"');
        }

        return new self();
    }
}

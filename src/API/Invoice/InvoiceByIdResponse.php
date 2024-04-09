<?php

namespace JustCommunication\PaykeeperSDK\API\Invoice;

use JustCommunication\PaykeeperSDK\API\AbstractResponse;
use JustCommunication\PaykeeperSDK\API\ResponseInterface;
use JustCommunication\PaykeeperSDK\Model\Invoice;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

class InvoiceByIdResponse extends AbstractResponse
{
    protected Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }

    public static function createFromResponse(HttpResponseInterface $response): ResponseInterface
    {
        $data = self::extractData($response);
        return new self(Invoice::createWithData($data));
    }
}

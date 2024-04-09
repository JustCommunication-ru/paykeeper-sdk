<?php

namespace JustCommunication\PaykeeperSDK\API\Invoice;

use JustCommunication\PaykeeperSDK\API\AbstractResponse;
use JustCommunication\PaykeeperSDK\API\ResponseInterface;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

class InvoicePreviewResponse extends AbstractResponse
{
    protected int $invoice_id;
    protected string $invoice_url;

    public function __construct(int $invoice_id, string $invoice_url)
    {
        $this->invoice_id = $invoice_id;
        $this->invoice_url = $invoice_url;
    }

    public function getInvoiceId(): int
    {
        return $this->invoice_id;
    }

    public function getInvoiceUrl(): string
    {
        return $this->invoice_url;
    }

    public static function createFromResponse(HttpResponseInterface $response): ResponseInterface
    {
        $data = self::extractData($response);

        return new self($data['invoice_id'], $data['invoice_url']);
    }
}

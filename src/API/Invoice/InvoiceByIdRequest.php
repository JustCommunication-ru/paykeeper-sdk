<?php

namespace JustCommunication\PaykeeperSDK\API\Invoice;

use JustCommunication\PaykeeperSDK\API\AbstractRequest;

class InvoiceByIdRequest extends AbstractRequest
{
    public const URI = '/info/invoice/byid/';
    public const HTTP_METHOD = 'GET';
    public const RESPONSE_CLASS = InvoiceByIdResponse::class;

    protected ?int $invoice_id;

    public function __construct(?int $invoice_id = null)
    {
        $this->invoice_id = $invoice_id;
    }

    public function getInvoiceId(): ?int
    {
        return $this->invoice_id;
    }

    public function setInvoiceId(?int $invoice_id): self
    {
        $this->invoice_id = $invoice_id;
        return $this;
    }

    public function createHttpClientParams(): array
    {
        return [
            'query' => [
                'id' => $this->invoice_id
            ]
        ];
    }
}

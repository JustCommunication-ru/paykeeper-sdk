<?php

namespace JustCommunication\PaykeeperSDK\API\Invoice;

use JustCommunication\PaykeeperSDK\API\AbstractRequest;

class InvoiceRevokeRequest extends AbstractRequest
{
    public const URI = '/change/invoice/revoke/';
    public const HTTP_METHOD = 'POST';
    public const RESPONSE_CLASS = InvoiceRevokeResponse::class;

    protected string $invoice_id;

    public function __construct(string $invoice_id)
    {
        $this->invoice_id = $invoice_id;
    }

    public function getInvoiceId(): string
    {
        return $this->invoice_id;
    }

    public function setInvoiceId(string $invoice_id): self
    {
        $this->invoice_id = $invoice_id;
        return $this;
    }

    public function createHttpClientParams(): array
    {
        $form_params = [
            'id' => $this->invoice_id
        ];

        return [
            'form_params' => $form_params
        ];
    }
}

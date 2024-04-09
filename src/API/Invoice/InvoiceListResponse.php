<?php

namespace JustCommunication\PaykeeperSDK\API\Invoice;

use JustCommunication\PaykeeperSDK\API\AbstractResponse;
use JustCommunication\PaykeeperSDK\API\ResponseInterface;
use JustCommunication\PaykeeperSDK\Model\Invoice;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

class InvoiceListResponse extends AbstractResponse
{
    /**
     * @var Invoice[]
     */
    protected array $invoices;

    /**
     * @param Invoice[] $invoices
     */
    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     * @return Invoice[]
     */
    public function getInvoices(): array
    {
        return $this->invoices;
    }

    public static function createFromResponse(HttpResponseInterface $response): ResponseInterface
    {
        $data = self::extractData($response);

        $invoices = [];
        foreach ($data as $invoice_data) {
            $invoices[] = Invoice::createWithData($invoice_data);
        }

        return new self($invoices);
    }
}

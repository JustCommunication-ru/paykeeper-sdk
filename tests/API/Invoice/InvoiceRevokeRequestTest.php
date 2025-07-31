<?php

namespace API\Invoice;

use JustCommunication\PaykeeperSDK\API\Invoice\InvoiceRevokeRequest;
use PHPUnit\Framework\TestCase;

class InvoiceRevokeRequestTest extends TestCase
{
    public function testCreate(): void
    {
        $invoicePreviewRequest = new InvoiceRevokeRequest('123');

        $this->assertEquals([
            'form_params' => [
                'id' => '123',
            ]
        ], $invoicePreviewRequest->createHttpClientParams());

        $invoicePreviewRequest->setInvoiceId('123456');

        $this->assertEquals([
            'form_params' => [
                'id' => '123456',
            ]
        ], $invoicePreviewRequest->createHttpClientParams());
    }
}

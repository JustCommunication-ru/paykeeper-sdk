<?php

namespace Tests\API\Invoice;

use JustCommunication\PaykeeperSDK\API\Invoice\CartItem;
use JustCommunication\PaykeeperSDK\API\Invoice\InvoicePreviewRequest;
use JustCommunication\PaykeeperSDK\API\Invoice\ServiceName;
use PHPUnit\Framework\TestCase;

class InvoicePreviewRequestTest extends TestCase
{
    public function testCreateWithServiceNameString()
    {
        $invoicePreviewRequest = new InvoicePreviewRequest();
        $invoicePreviewRequest
            ->setOrderId(123)
            ->setServiceName('Test service')
            ->setAmount(100)
        ;

        $this->assertEquals([
            'form_params' => [
                'orderid' => '123',
                'service_name' => 'Test service',
                'pay_amount' => 100.0,
                'clientid' => null,
                'client_email' => null,
                'client_phone' => null
            ]
        ], $invoicePreviewRequest->createHttpClientParams());
    }

    public function testCreateWithServiceNameObject()
    {
        $invoicePreviewRequest = new InvoicePreviewRequest();
        $invoicePreviewRequest
            ->setOrderId(123)
            ->setAmount(100)
        ;

        $serviceNameObject = new ServiceName();
        $serviceNameObject
            ->setServiceName('Test service')
            ->addCartItem(new CartItem('Первая позиция', 100, 2, 200, 'none'))
            ->addCartItem(new CartItem('Вторая позиция', 100, 2, 200, 'none'))
        ;

        $invoicePreviewRequest->setServiceNameObject($serviceNameObject);

        $this->assertEquals([
            'form_params' => [
                'orderid' => '123',
                'service_name' => '{"service_name":"Test service","cart":[{"name":"Первая позиция","price":100,"quantity":2,"sum":200,"tax":"none"},{"name":"Вторая позиция","price":100,"quantity":2,"sum":200,"tax":"none"}]}',
                'pay_amount' => 100.0,
                'clientid' => null,
                'client_email' => null,
                'client_phone' => null
            ]
        ], json_decode(json_encode($invoicePreviewRequest->createHttpClientParams()), true));
    }
}

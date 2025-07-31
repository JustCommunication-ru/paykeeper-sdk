<?php

namespace API\Invoice;

use GuzzleHttp\Psr7\Response;
use JustCommunication\PaykeeperSDK\API\Invoice\InvoiceRevokeResponse;
use JustCommunication\PaykeeperSDK\Exception\PaykeeperAPIException;
use PHPUnit\Framework\TestCase;

class InvoiceRevokeResponseTest extends TestCase
{
    public function testCreateResponse(): void
    {
        $httpResponse = new Response(200, [], '{"result":"success"}');

        $response = InvoiceRevokeResponse::createFromResponse($httpResponse);
        $this->assertInstanceOf(InvoiceRevokeResponse::class, $response);
    }

    public function testCreateResponseWithNoSuccessResult(): void
    {
        $httpResponse = new Response(200, [], '{"result":"failure"}');

        $this->expectException(PaykeeperAPIException::class);
        $this->expectExceptionMessage('Response `result` is not "success", but "failure"');
        InvoiceRevokeResponse::createFromResponse($httpResponse);
    }

    public function testCreateResponseWithNoResult(): void
    {
        $httpResponse = new Response(200, [], '{"noresult":"success"}');

        $this->expectException(PaykeeperAPIException::class);
        $this->expectExceptionMessage('Missing `result` from response');
        InvoiceRevokeResponse::createFromResponse($httpResponse);
    }

    public function testCreateResponseWithNoJson(): void
    {
        $httpResponse = new Response(200, [], 'blabla');

        $this->expectException(PaykeeperAPIException::class);
        $this->expectExceptionMessage('Unable to decode response data. Error: Syntax error');
        InvoiceRevokeResponse::createFromResponse($httpResponse);
    }
}

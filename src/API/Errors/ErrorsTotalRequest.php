<?php

namespace JustCommunication\PaykeeperSDK\API\Errors;

use JustCommunication\PaykeeperSDK\API\AbstractRequest;

class ErrorsTotalRequest extends AbstractRequest
{
    public const URI = '/info/errors/total/';
    public const HTTP_METHOD = 'GET';
    public const RESPONSE_CLASS = ErrorsTotalResponse::class;
}

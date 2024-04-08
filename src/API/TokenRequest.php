<?php

namespace JustCommunication\PaykeeperSDK\API;

use JustCommunication\PaykeeperSDK\API\AbstractRequest;

class TokenRequest extends AbstractRequest
{
    public const URI = '/info/settings/token/';
    public const HTTP_METHOD = 'GET';
    public const RESPONSE_CLASS = TokenResponse::class;
}

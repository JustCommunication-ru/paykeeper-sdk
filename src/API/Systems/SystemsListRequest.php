<?php

namespace JustCommunication\PaykeeperSDK\API\Systems;

use JustCommunication\PaykeeperSDK\API\AbstractRequest;

class SystemsListRequest extends AbstractRequest
{
    public const URI = '/info/systems/list/';
    public const HTTP_METHOD = 'GET';
    public const RESPONSE_CLASS = SystemsListResponse::class;
}

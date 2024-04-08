<?php

namespace JustCommunication\PaykeeperSDK\API\Errors;

use JustCommunication\PaykeeperSDK\API\AbstractResponse;
use JustCommunication\PaykeeperSDK\API\ResponseInterface;
use JustCommunication\PaykeeperSDK\Model\ErrorGroup;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

class ErrorsTotalResponse extends AbstractResponse
{
    protected int $total;

    /**
     * @var ErrorGroup[]
     */
    protected array $groups;

    /**
     * @param ErrorGroup[]  $groups
     */
    public function __construct(int $total, array $groups)
    {
        $this->total = $total;
        $this->groups = $groups;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return ErrorGroup[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public static function createFromResponse(HttpResponseInterface $response): ResponseInterface
    {
        $data = self::extractData($response);

        $groups = [];
        foreach ($data['totalWithGroup'] as $group_data) {
            $groups[] = ErrorGroup::createWithData($group_data);
        }

        return new self($data['total'], $groups);
    }
}

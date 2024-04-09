<?php

namespace JustCommunication\PaykeeperSDK\API\Invoice;

use DateTime;
use JustCommunication\PaykeeperSDK\API\AbstractRequest;

class InvoiceListRequest extends AbstractRequest
{
    public const URI = '/info/invoice/list/';
    public const HTTP_METHOD = 'GET';
    public const RESPONSE_CLASS = InvoiceListResponse::class;

    public const STATUS_SENT = 'sent';
    public const STATUS_PAID = 'paid';
    public const STATUS_EXPIRED = 'expired';

    protected array $statuses = [];
    protected ?DateTime $startDate = null;
    protected ?DateTime $endDate = null;
    protected int $from = 0;
    protected int $limit = 100;

    public function getStatuses(): array
    {
        return $this->statuses;
    }

    public function setStatuses(array $statuses): self
    {
        $this->statuses = $statuses;
        return $this;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTime $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getFrom(): int
    {
        return $this->from;
    }

    public function setFrom(int $from): self
    {
        $this->from = $from;
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function createHttpClientParams(): array
    {
        $query_params = [
            'from' => $this->from,
            'limit' => $this->limit
        ];

        if ($this->statuses) {
            $query_params['status'] = $this->statuses;
        }

        if ($this->startDate) {
            $query_params['start_date'] = $this->startDate->format('Y-m-d');
        }

        if ($this->endDate) {
            $query_params['end_date'] = $this->endDate->format('Y-m-d');
        }

        return [
            'query' => $query_params
        ];
    }
}

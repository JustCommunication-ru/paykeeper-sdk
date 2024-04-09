<?php

namespace JustCommunication\PaykeeperSDK\Model;

use DateTime;
use DateTimeZone;

class Invoice
{
    public const STATUS_PAID = 'paid';

    protected int $id;
    protected int $user_id;
    protected string $status;
    protected float $pay_amount;
    protected string $client_id;
    protected string $order_id;
    protected ?int $payment_id;
    protected string $service_name;
    protected string $client_phone;
    protected string $client_email;
    protected DateTime $createdAt;
    protected ?DateTime $expireAt;
    protected ?DateTime $paidAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isPaid(): bool
    {
        return $this->getStatus() === self::STATUS_PAID;
    }

    public function getPayAmount(): float
    {
        return $this->pay_amount;
    }

    public function getClientId(): string
    {
        return $this->client_id;
    }

    public function getOrderId(): string
    {
        return $this->order_id;
    }

    public function getPaymentId(): ?int
    {
        return $this->payment_id;
    }

    public function getServiceName(): string
    {
        return $this->service_name;
    }

    public function getClientPhone(): string
    {
        return $this->client_phone;
    }

    public function getClientEmail(): string
    {
        return $this->client_email;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getExpireAt(): ?DateTime
    {
        return $this->expireAt;
    }

    public function getPaidAt(): ?DateTime
    {
        return $this->paidAt;
    }

    public static function createWithData(array $data): self
    {
        $self = new self();
        $self->id = $data['id'];
        $self->user_id = $data['user_id'];
        $self->status = $data['status'];
        $self->pay_amount = $data['pay_amount'];
        $self->client_id = $data['clientid'] ?? '';
        $self->order_id = $data['orderid'] ?? '';
        $self->payment_id = $data['paymentid'];
        $self->service_name = $data['service_name'] ?? '';
        $self->client_email = $data['client_email'] ?? '';
        $self->client_phone = $data['client_phone'] ?? '';
        $self->expireAt = $data['expiry_datetime'] ? new DateTime($data['expiry_datetime'], new DateTimeZone('Europe/Moscow')) : null;
        $self->createdAt = new DateTime($data['created_datetime'], new DateTimeZone('Europe/Moscow'));
        $self->paidAt = $data['paid_datetime'] ? new DateTime($data['paid_datetime'], new DateTimeZone('Europe/Moscow')) : null;

        return $self;
    }
}

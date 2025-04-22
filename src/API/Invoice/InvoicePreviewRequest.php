<?php

namespace JustCommunication\PaykeeperSDK\API\Invoice;

use DateTime;
use JustCommunication\PaykeeperSDK\API\AbstractRequest;

class InvoicePreviewRequest extends AbstractRequest
{
    public const URI = '/change/invoice/preview/';
    public const HTTP_METHOD = 'POST';
    public const RESPONSE_CLASS = InvoicePreviewResponse::class;

    protected ?float $amount = null;
    protected ?string $client_id = null;
    protected ?string $order_id = null;
    protected ?ServiceName $serviceNameObject = null;
    protected ?string $service_name = null;
    protected ?string $client_email = null;
    protected ?string $client_phone = null;
    protected ?DateTime $expireAt = null;

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getClientId(): ?string
    {
        return $this->client_id;
    }

    public function setClientId(?string $client_id): self
    {
        $this->client_id = $client_id;
        return $this;
    }

    public function getOrderId(): ?string
    {
        return $this->order_id;
    }

    public function setOrderId(?string $order_id): self
    {
        $this->order_id = $order_id;
        return $this;
    }

    public function getServiceNameObject(): ?ServiceName
    {
        return $this->serviceNameObject;
    }

    public function setServiceNameObject(?ServiceName $serviceNameObject): self
    {
        $this->serviceNameObject = $serviceNameObject;
        return $this;
    }

    public function getServiceName(): ?string
    {
        return $this->service_name;
    }

    public function setServiceName(?string $service_name): self
    {
        $this->service_name = $service_name;
        return $this;
    }

    public function getClientEmail(): ?string
    {
        return $this->client_email;
    }

    public function setClientEmail(?string $client_email): self
    {
        $this->client_email = $client_email;
        return $this;
    }

    public function getClientPhone(): ?string
    {
        return $this->client_phone;
    }

    public function setClientPhone(?string $client_phone): self
    {
        $this->client_phone = $client_phone;
        return $this;
    }

    public function getExpireAt(): ?DateTime
    {
        return $this->expireAt;
    }

    public function setExpireAt(?DateTime $expireAt): self
    {
        $this->expireAt = $expireAt;
        return $this;
    }
    
    public function createHttpClientParams(): array
    {
        $form_params = [
            'pay_amount' => $this->amount,
            'client_id' => $this->client_id,
            'order_id' => $this->order_id,
            'service_name' => $this->service_name,
            'client_email' => $this->client_email,
            'client_phone' => $this->client_phone
        ];

        if ($this->serviceNameObject) {
            $form_params['service_name'] = json_encode($this->serviceNameObject, JSON_UNESCAPED_UNICODE);
        } elseif ($this->service_name) {
            $form_params['service_name'] = $this->service_name;
        }

        if ($this->expireAt) {
            $form_params['expiry_at'] = $this->expireAt->format('Y-m-d H:i:s');
        }

        return [
            'form_params' => $form_params
        ];
    }
}

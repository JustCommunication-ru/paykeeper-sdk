<?php

namespace JustCommunication\PaykeeperSDK\API\Invoice;

use JsonSerializable;

class ServiceName implements JsonSerializable
{
    protected ?string $service_name = null;
    protected ?string $user_result_callback = null;
    protected ?string $lang = null;
    protected array $receipt_properties = [];

    /**
     * @var CartItem[]
     */
    protected array $cart = [];

    public function getServiceName(): ?string
    {
        return $this->service_name;
    }

    public function setServiceName(?string $service_name): self
    {
        $this->service_name = $service_name;
        return $this;
    }

    public function getUserResultCallback(): ?string
    {
        return $this->user_result_callback;
    }

    public function setUserResultCallback(?string $user_result_callback): self
    {
        $this->user_result_callback = $user_result_callback;
        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(?string $lang): self
    {
        $this->lang = $lang;
        return $this;
    }

    public function getReceiptProperties(): array
    {
        return $this->receipt_properties;
    }

    public function setReceiptProperties(array $receipt_properties): self
    {
        $this->receipt_properties = $receipt_properties;
        return $this;
    }

    public function getCart(): array
    {
        return $this->cart;
    }

    public function setCart(array $cart): self
    {
        $this->cart = $cart;
        return $this;
    }

    public function addCartItem(CartItem $cartItem): self
    {
        $this->cart[] = $cartItem;
        return $this;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $data = [];

        if ($this->service_name) {
            $data['service_name'] = $this->service_name;
        }

        if ($this->user_result_callback) {
            $data['user_result_callback'] = $this->user_result_callback;
        }

        if ($this->lang) {
            $data['lang'] = $this->lang;
        }

        if ($this->receipt_properties) {
            $data['receipt_properties'] = $this->receipt_properties;
        }

        if ($this->cart) {
            $data['cart'] = $this->cart;
        }

        return $data;
    }
}

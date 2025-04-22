<?php

namespace JustCommunication\PaykeeperSDK\API\Invoice;

use JsonSerializable;

class CartItem implements JsonSerializable
{
    protected string $name;
    protected int $price;
    protected int $quantity;
    protected int $sum;
    protected string $tax;

    public function __construct(string $name, int $price, int $quantity, int $sum, string $tax)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->sum = $sum;
        $this->tax = $tax;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $data = [
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'sum' => $this->sum,
            'tax' => $this->tax
        ];

        return $data;
    }
}

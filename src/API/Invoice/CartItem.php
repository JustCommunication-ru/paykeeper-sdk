<?php

namespace JustCommunication\PaykeeperSDK\API\Invoice;

use JsonSerializable;

class CartItem implements JsonSerializable
{
    const ITEM_TYPE_GOODS = 'goods';
    const ITEM_TYPE_SERVICE = 'service';
    const ITEM_TYPE_WORK = 'work';
    const ITEM_TYPE_EXCISE = 'excise';
    const ITEM_TYPE_IP = 'ip';
    const ITEM_TYPE_PAYMENT = 'payment';
    const ITEM_TYPE_AGENT = 'agent';
    const ITEM_TYPE_PROPERTY_RIGHT = 'property_right';
    const ITEM_TYPE_NON_OPERATING = 'non_operating';
    const ITEM_TYPE_SALES_TAX = 'sales_tax';
    const ITEM_TYPE_RESORT_FEE = 'resort_fee';
    const ITEM_TYPE_OTHER = 'other';
    const ITEM_TYPE_EXC_UNCODED = 'exc_uncoded';
    const ITEM_TYPE_EXC_CODED = 'exc_coded';
    const ITEM_TYPE_GOODS_UNCODED = 'goods_uncoded';
    const ITEM_TYPE_GOODS_CODED = 'goods_coded';

    const PAYMENT_TYPE_PREPAY = 'prepay';
    const PAYMENT_TYPE_PART_PREPAY = 'part_prepay';
    const PAYMENT_TYPE_ADVANCE = 'advance';
    const PAYMENT_TYPE_FULL = 'full';
    const PAYMENT_TYPE_PART_CREDIT = 'part_credit';
    const PAYMENT_TYPE_CREDIT = 'credit';
    const PAYMENT_TYPE_CREDIT_PAYMENT = 'credit_payment';

    protected string $name;
    protected int $price;
    protected int $quantity;
    protected int $sum;
    protected string $tax;
    protected ?string $item_type;
    protected ?string $payment_type;
    protected ?string $item_id;
    protected ?string $item_code;
    protected ?string $item_code_b64;
    protected ?int $items_in_package;
    protected ?int $items_sold_from_package;
    protected ?string $measure;
    protected ?string $item_country;
    protected ?string $customs_declaration;
    protected ?string $excise;

    public function __construct(
        string $name,
        int $price,
        int $quantity,
        int $sum,
        string $tax,
        ?string $item_type = null,
        ?string $payment_type = null,
        ?string $item_id = null,
        ?string $item_code = null,
        ?string $item_code_b64 = null,
        ?int $items_in_package = null,
        ?int $items_sold_from_package = null,
        ?string $measure = null,
        ?string $item_country = null,
        ?string $customs_declaration = null,
        ?string $excise = null
    ) {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->sum = $sum;
        $this->tax = $tax;
        $this->item_type = $item_type;
        $this->payment_type = $payment_type;
        $this->item_id = $item_id;
        $this->item_code = $item_code;
        $this->item_code_b64 = $item_code_b64;
        $this->items_in_package = $items_in_package;
        $this->items_sold_from_package = $items_sold_from_package;
        $this->measure = $measure;
        $this->item_country = $item_country;
        $this->customs_declaration = $customs_declaration;
        $this->excise = $excise;
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

        if ($this->item_type) {
            $data['item_type'] = $this->item_type;
        }

        if ($this->payment_type) {
            $data['payment_type'] = $this->payment_type;
        }

        if ($this->item_id) {
            $data['item_id'] = $this->item_id;
        }

        if ($this->item_code) {
            $data['item_code'] = $this->item_code;
        }

        if ($this->item_code_b64) {
            $data['item_code_b64'] = $this->item_code_b64;
        }

        if ($this->items_in_package) {
            $data['items_in_package'] = $this->items_in_package;
        }

        if ($this->items_sold_from_package) {
            $data['items_sold_from_package'] = $this->items_sold_from_package;
        }

        if ($this->measure) {
            $data['measure'] = $this->measure;
        }

        if ($this->item_country) {
            $data['item_country'] = $this->item_country;
        }

        if ($this->customs_declaration) {
            $data['customs_declaration'] = $this->customs_declaration;
        }

        if ($this->excise) {
            $data['excise'] = $this->excise;
        }

        return $data;
    }
}

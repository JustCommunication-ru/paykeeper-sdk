<?php

namespace JustCommunication\PaykeeperSDK\Model;

class ErrorGroup
{
    protected int $code;
    protected int $total;
    protected string $description;

    public function getCode(): int
    {
        return $this->code;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public static function createWithData(array $data): self
    {
        $self = new self();
        $self->code = $data['error_code'] ?? 0;
        $self->total = $data['total'] ?? 0;
        $self->description = $data['user_description'] ?? '';

        return $self;
    }
}

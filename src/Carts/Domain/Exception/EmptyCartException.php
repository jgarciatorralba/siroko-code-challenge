<?php

declare(strict_types=1);

namespace App\Carts\Domain\Exception;

use App\Shared\Domain\DomainException;
use App\Shared\Domain\ValueObject\Uuid;

class EmptyCartException extends DomainException
{
    public function __construct(private readonly Uuid $id)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'empty_cart';
    }

    public function errorMessage(): string
    {
        return sprintf(
            "Cart with id '%s' is empty and cannot be confirmed.",
            $this->id->value()
        );
    }
}

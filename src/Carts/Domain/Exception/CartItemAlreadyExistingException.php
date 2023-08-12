<?php

declare(strict_types=1);

namespace App\Carts\Domain\Exception;

use App\Shared\Domain\DomainException;
use App\Shared\Domain\ValueObject\Uuid;

class CartItemAlreadyExistingException extends DomainException
{
    public function __construct(
        private readonly Uuid $productId,
        private readonly Uuid $cartId
    ) {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'cart_item_already_existing';
    }

    public function errorMessage(): string
    {
        return sprintf(
            "Cart item for product with id '%s' already exists in cart with id '%s'.",
            $this->productId->value(),
            $this->cartId->value()
        );
    }
}

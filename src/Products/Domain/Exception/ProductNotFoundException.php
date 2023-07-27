<?php

declare(strict_types=1);

namespace App\Products\Domain\Exception;

use App\Shared\Domain\DomainException;
use App\Shared\Domain\ValueObject\Uuid;

class ProductNotFoundException extends DomainException
{
    public function __construct(private readonly Uuid $id)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'product_not_found';
    }

    public function errorMessage(): string
    {
        return sprintf(
            "Product with id '%s' could not be found.",
            $this->id->value()
        );
    }
}

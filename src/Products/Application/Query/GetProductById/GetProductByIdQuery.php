<?php

declare(strict_types=1);

namespace App\Products\Application\Query\GetProductById;

use App\Shared\Domain\Bus\Query\Query;

final class GetProductByIdQuery implements Query
{
    public function __construct(
        private readonly string $id
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }
}

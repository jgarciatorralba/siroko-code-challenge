<?php

declare(strict_types=1);

namespace App\Carts\Application\Query\GetCartById;

use App\Shared\Domain\Bus\Query\Query;

final class GetCartByIdQuery implements Query
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

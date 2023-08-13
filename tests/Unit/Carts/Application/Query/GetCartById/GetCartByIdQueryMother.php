<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\Application\Query\GetCartById;

use App\Carts\Application\Query\GetCartById\GetCartByIdQuery;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;

final class GetCartByIdQueryMother
{
    public static function create(
        string $id = null,
    ): GetCartByIdQuery {
        return new GetCartByIdQuery(
            id: $id ?? FakeValueGenerator::uuid()->value()
        );
    }
}

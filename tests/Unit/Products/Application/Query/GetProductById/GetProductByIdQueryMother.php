<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\Application\Query\GetProductById;

use App\Products\Application\Query\GetProductById\GetProductByIdQuery;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;

final class GetProductByIdQueryMother
{
    public static function create(
        string $id = null,
    ): GetProductByIdQuery {
        return new GetProductByIdQuery(
            id: $id ?? FakeValueGenerator::uuid()->value()
        );
    }
}

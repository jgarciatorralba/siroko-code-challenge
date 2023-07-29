<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\Domain;

use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use DateTimeImmutable;

final class ProductMother
{
    public static function create(
        Uuid $id = null,
        string $name = null,
        float $price = null,
        DateTimeImmutable $createdAt = null,
        DateTimeImmutable $updatedAt = null
    ): Product {
        return new Product(
            id: $id ?? FakeValueGenerator::uuid(),
            name: $name ?? FakeValueGenerator::string(),
            price: $price ?? FakeValueGenerator::float(0, 100),
            createdAt: $createdAt ?? FakeValueGenerator::dateTime(),
            updatedAt: $updatedAt ?? FakeValueGenerator::dateTime()
        );
    }
}

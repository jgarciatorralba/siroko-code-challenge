<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\Application\Command\UpdateProduct;

use App\Products\Application\Command\UpdateProduct\UpdateProductCommand;
use App\Products\Domain\Product;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use DateTimeImmutable;

final class UpdateProductCommandMother
{
    public static function create(
        ?string $id = null,
        ?string $name = null,
        ?float $price = null,
        ?DateTimeImmutable $updatedAt = null
    ): UpdateProductCommand {
        return new UpdateProductCommand(
            id: $id ?? FakeValueGenerator::uuid()->value(),
            name: $name ?? FakeValueGenerator::string(),
            price: $price ?? FakeValueGenerator::float(1, 1000),
            updatedAt: $updatedAt ?? FakeValueGenerator::dateTime()
        );
    }

    public static function createFromProduct(Product $product): UpdateProductCommand
    {
        return self::create(
            id: $product->id()->value(),
            name: $product->name(),
            price: $product->price(),
            updatedAt: $product->updatedAt()
        );
    }
}

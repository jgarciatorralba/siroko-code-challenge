<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\Application\Command\CreateProduct;

use App\Products\Application\Command\CreateProduct\CreateProductCommand;
use App\Products\Domain\Product;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use DateTimeImmutable;

final class CreateProductCommandMother
{
    public static function create(
        ?string $id = null,
        ?string $name = null,
        ?float $price = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null
    ): CreateProductCommand {
        return new CreateProductCommand(
            id: $id ?? FakeValueGenerator::uuid()->value(),
            name: $name ?? FakeValueGenerator::string(),
            price: $price ?? FakeValueGenerator::float(10, 1000),
            createdAt: $createdAt ?? FakeValueGenerator::dateTime(),
            updatedAt: $updatedAt ?? FakeValueGenerator::dateTime()
        );
    }

    public static function createFromProduct(Product $product): CreateProductCommand
    {
        return self::create(
            id: $product->id()->value(),
            name: $product->name(),
            price: $product->price(),
            createdAt: $product->createdAt(),
            updatedAt: $product->updatedAt()
        );
    }
}

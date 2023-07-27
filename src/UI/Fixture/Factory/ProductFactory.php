<?php

declare(strict_types=1);

namespace App\UI\Fixture\Factory;

use App\Shared\Domain\ValueObject\Uuid;
use App\Products\Domain\Product;
use DateTimeImmutable;

final class ProductFactory extends ModelFactory
{
    protected function getModelClass(): string
    {
        return Product::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaultAttributes(): array
    {
        return [
            'id' => Uuid::random(),
            'name' => $this->faker()->name(),
            'price' => $this->faker()->randomFloat(2, 0, 1000),
            'createdAt' => DateTimeImmutable::createFromMutable($this->faker()->dateTimeBetween('-1 year')),
            'updatedAt' => DateTimeImmutable::createFromMutable($this->faker()->dateTimeBetween('-1 year'))
        ];
    }
}

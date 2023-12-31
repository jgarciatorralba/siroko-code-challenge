<?php

declare(strict_types=1);

namespace App\UI\Fixture\Factory;

use App\Shared\Domain\ValueObject\Uuid;
use App\Carts\Domain\Cart;
use App\Shared\Utils;
use DateTimeImmutable;

final class CartFactory extends ModelFactory
{
    protected function getModelClass(): string
    {
        return Cart::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaultAttributes(): array
    {
        $createdAt = DateTimeImmutable::createFromMutable(
            $this->faker()->dateTimeBetween('-1 year')
        );

        return [
            'id' => Uuid::random(),
            'isConfirmed' => $this->faker()->boolean(),
            'subtotal' => null,
            'createdAt' => $createdAt,
            'updatedAt' => DateTimeImmutable::createFromMutable(
                $this->faker()->dateTimeBetween(Utils::dateToString($createdAt))
            )
        ];
    }
}

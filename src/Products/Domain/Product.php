<?php

declare(strict_types=1);

namespace App\Products\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Aggregate\Timestampable;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Utils;
use DateTimeImmutable;

class Product extends AggregateRoot
{
    use Timestampable;

    public function __construct(
        private Uuid $id,
        private string $name,
        private float $price,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ) {
        $this->updateCreatedAt($createdAt);
        $this->updateUpdatedAt($updatedAt);
    }

    public static function create(
        Uuid $id,
        string $name,
        float $price
    ): self {
        return new self(
            id: $id,
            name: $name,
            price: $price,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
    }

    public function updatePrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return array<string, string|array<mixed>>
     */
    public function toArray(bool $isNestedArray = false): array
    {
        $productArray = [
            'id' => $this->id->value(),
            'name' => $this->name,
            'price' => $this->price,
            'created_at' => Utils::dateToString($this->createdAt),
            'updated_at' => Utils::dateToString($this->updatedAt)
        ];

        return $productArray;
    }
}

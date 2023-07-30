<?php

declare(strict_types=1);

namespace App\Products\Application\Command\CreateProduct;

use App\Shared\Domain\Bus\Command\Command;
use DateTimeImmutable;

final class CreateProductCommand implements Command
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly float $price,
        private readonly DateTimeImmutable $createdAt,
        private readonly DateTimeImmutable $updatedAt
    ) {
    }

    public function id(): string
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

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}

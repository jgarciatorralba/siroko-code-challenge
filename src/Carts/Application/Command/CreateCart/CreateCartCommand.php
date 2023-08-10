<?php

declare(strict_types=1);

namespace App\Carts\Application\Command\CreateCart;

use App\Shared\Domain\Bus\Command\Command;
use DateTimeImmutable;

final class CreateCartCommand implements Command
{
    /** @param array<string, string|int> $items */
    public function __construct(
        private readonly string $id,
        private readonly array $items,
        private readonly DateTimeImmutable $createdAt,
        private readonly DateTimeImmutable $updatedAt
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    /** @return array<string, string|int> */
    public function items(): array
    {
        return $this->items;
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

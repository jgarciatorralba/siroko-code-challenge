<?php

declare(strict_types=1);

namespace App\Carts\Application\Command\UpdateCart;

use App\Shared\Domain\Bus\Command\Command;
use DateTimeImmutable;

final class UpdateCartCommand implements Command
{
    /** @param array<string, string|int> $operations */
    public function __construct(
        private readonly string $id,
        private readonly array $operations,
        private readonly DateTimeImmutable $updatedAt
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    /** @return array<string, string|int> */
    public function operations(): array
    {
        return $this->operations;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}

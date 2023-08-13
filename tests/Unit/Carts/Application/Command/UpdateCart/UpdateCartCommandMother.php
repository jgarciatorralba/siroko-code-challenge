<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\Application\Command\UpdateCart;

use App\Carts\Application\Command\UpdateCart\UpdateCartCommand;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use DateTimeImmutable;

final class UpdateCartCommandMother
{
    /**
     * @param array<array<string, string|int>> $operations
     */
    public static function create(
        ?string $id = null,
        ?array $operations = null,
        ?DateTimeImmutable $updatedAt = null
    ): UpdateCartCommand {
        return new UpdateCartCommand(
            id: $id ?? FakeValueGenerator::uuid()->value(),
            operations: $operations ?? [],
            updatedAt: $updatedAt ?? FakeValueGenerator::dateTime()
        );
    }
}

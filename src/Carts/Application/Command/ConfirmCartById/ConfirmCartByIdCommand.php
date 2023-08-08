<?php

declare(strict_types=1);

namespace App\Carts\Application\Command\ConfirmCartById;

use App\Shared\Domain\Bus\Command\Command;

final class ConfirmCartByIdCommand implements Command
{
    public function __construct(
        private readonly string $id
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }
}

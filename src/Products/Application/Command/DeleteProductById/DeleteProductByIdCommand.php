<?php

declare(strict_types=1);

namespace App\Products\Application\Command\DeleteProductById;

use App\Shared\Domain\Bus\Command\Command;

final class DeleteProductByIdCommand implements Command
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

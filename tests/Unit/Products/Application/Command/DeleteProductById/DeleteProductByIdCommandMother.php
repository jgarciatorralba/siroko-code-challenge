<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\Application\Command\DeleteProductById;

use App\Products\Application\Command\DeleteProductById\DeleteProductByIdCommand;
use App\Products\Domain\Product;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;

final class DeleteProductByIdCommandMother
{
    public static function create(
        ?string $id = null
    ): DeleteProductByIdCommand {
        return new DeleteProductByIdCommand(
            id: $id ?? FakeValueGenerator::uuid()->value()
        );
    }

    public static function createFromProduct(Product $product): DeleteProductByIdCommand
    {
        return self::create(
            id: $product->id()->value()
        );
    }
}

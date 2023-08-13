<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\Application\Command\DeleteCartById;

use App\Carts\Application\Command\DeleteCartById\DeleteCartByIdCommand;
use App\Carts\Domain\Cart;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;

final class DeleteCartByIdCommandMother
{
    public static function create(
        ?string $id = null
    ): DeleteCartByIdCommand {
        return new DeleteCartByIdCommand(
            id: $id ?? FakeValueGenerator::uuid()->value()
        );
    }

    public static function createFromCart(Cart $cart): DeleteCartByIdCommand
    {
        return self::create(
            id: $cart->id()->value()
        );
    }
}

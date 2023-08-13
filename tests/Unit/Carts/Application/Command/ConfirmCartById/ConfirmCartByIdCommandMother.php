<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\Application\Command\ConfirmCartById;

use App\Carts\Application\Command\ConfirmCartById\ConfirmCartByIdCommand;
use App\Carts\Domain\Cart;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;

final class ConfirmCartByIdCommandMother
{
    public static function create(
        ?string $id = null
    ): ConfirmCartByIdCommand {
        return new ConfirmCartByIdCommand(
            id: $id ?? FakeValueGenerator::uuid()->value()
        );
    }

    public static function createFromCart(Cart $cart): ConfirmCartByIdCommand
    {
        return self::create(
            id: $cart->id()->value()
        );
    }
}

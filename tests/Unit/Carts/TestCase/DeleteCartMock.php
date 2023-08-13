<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\TestCase;

use App\Carts\Domain\Cart;
use App\Carts\Domain\Exception\CartAlreadyConfirmedException;
use App\Carts\Domain\Service\DeleteCart;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class DeleteCartMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return DeleteCart::class;
    }

    public function shouldDeleteCart(Cart $cart): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($cart);
    }

    public function shouldThrowException(Cart $cart): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($cart)
            ->willThrowException(new CartAlreadyConfirmedException($cart->id()));
    }
}

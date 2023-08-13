<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\TestCase;

use App\Carts\Domain\Cart;
use App\Carts\Domain\Exception\CartAlreadyConfirmedException;
use App\Carts\Domain\Exception\EmptyCartException;
use App\Carts\Domain\Service\ConfirmCart;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class ConfirmCartMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return ConfirmCart::class;
    }

    public function shouldConfirmCart(Cart $cart): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($cart);
    }

    public function shouldThrowEmptyCartException(Cart $cart): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($cart)
            ->willThrowException(new EmptyCartException($cart->id()));
    }

    public function shouldThrowCartAlreadyConfirmedException(Cart $cart): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($cart)
            ->willThrowException(new CartAlreadyConfirmedException($cart->id()));
    }
}

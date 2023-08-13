<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\TestCase;

use App\Carts\Domain\Cart;
use App\Carts\Domain\Exception\CartAlreadyConfirmedException;
use App\Carts\Domain\Exception\CartItemAlreadyExistingException;
use App\Carts\Domain\Exception\CartItemNotFoundException;
use App\Carts\Domain\Service\UpdateCart;
use App\Products\Domain\Product;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class UpdateCartMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return UpdateCart::class;
    }

    public function shouldUpdateCart(Cart $cart): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($cart);
    }

    public function shouldThrowCartAlreadyConfirmedException(Cart $cart): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($cart)
            ->willThrowException(new CartAlreadyConfirmedException($cart->id()));
    }

    public function shouldThrowCartItemAlreadyExistingException(Cart $cart, Product $product): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($cart)
            ->willThrowException(new CartItemAlreadyExistingException(
                $product->id(),
                $cart->id()
            ));
    }

    public function shouldThrowCartItemNotFoundException(Cart $cart, Product $product): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($cart)
            ->willThrowException(new CartItemNotFoundException(
                $product->id(),
                $cart->id()
            ));
    }
}

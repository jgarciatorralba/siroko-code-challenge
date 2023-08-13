<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\TestCase;

use App\Carts\Domain\Cart;
use App\Carts\Domain\Service\CreateCart;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class CreateCartMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return CreateCart::class;
    }

    public function shouldCreateCart(Cart $cart): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($cart);
    }

    public function shouldNotCreateCart(Cart $cart): void
    {
        $this->mock
            ->expects($this->never())
            ->method('__invoke')
            ->with($cart);
    }
}

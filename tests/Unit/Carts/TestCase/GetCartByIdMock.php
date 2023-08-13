<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\TestCase;

use App\Carts\Domain\Exception\CartNotFoundException;
use App\Carts\Domain\Cart;
use App\Carts\Domain\Service\GetCartById;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class GetCartByIdMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return GetCartById::class;
    }

    public function shouldReturnCart(Uuid $id, Cart $cart): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($id)
            ->willReturn($cart);
    }

    public function shouldThrowException(Uuid $id): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($id)
            ->willThrowException(new CartNotFoundException($id));
    }
}

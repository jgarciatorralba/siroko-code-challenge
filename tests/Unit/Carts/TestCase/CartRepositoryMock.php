<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\TestCase;

use App\Carts\Domain\Contract\CartRepository;
use App\Carts\Domain\Cart;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class CartRepositoryMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return CartRepository::class;
    }

    /**
     * @param Cart[] $carts
     */
    public function shouldFindCarts(array $carts): void
    {
        $this->mock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($carts);
    }

    public function shouldNotFindCarts(): void
    {
        $this->mock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
    }

    public function shouldFindCartById(Uuid $id, Cart $cart): void
    {
        $this->mock
            ->expects($this->once())
            ->method('findOneById')
            ->with($id)
            ->willReturn($cart);
    }

    public function shouldNotFindCartById(Uuid $id): void
    {
        $this->mock
            ->expects($this->once())
            ->method('findOneById')
            ->with($id)
            ->willReturn(null);
    }

    public function shouldCreateCart(Cart $cart): void
    {
        $this->mock
            ->expects($this->once())
            ->method('create')
            ->with($cart);
    }

    public function shouldDeleteCart(Cart $cart): void
    {
        $this->mock
            ->expects($this->once())
            ->method('delete')
            ->with($cart);
    }

    public function shouldNotDeleteCart(Cart $cart): void
    {
        $this->mock
            ->expects($this->never())
            ->method('delete')
            ->with($cart);
    }
}

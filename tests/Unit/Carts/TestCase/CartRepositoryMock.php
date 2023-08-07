<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\TestCase;

use App\Carts\Domain\Contract\CartRepository;
use App\Carts\Domain\Cart;
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
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\TestCase;

use App\Carts\Domain\Cart;
use App\Carts\Domain\Service\GetCarts;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class GetCartsMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return GetCarts::class;
    }

    /**
     * @param Cart[] $carts
     */
    public function shouldGetAllCarts(array $carts): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->willReturn($carts);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\TestCase;

use App\Products\Domain\Product;
use App\Products\Domain\Service\GetProducts;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class GetProductsMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return GetProducts::class;
    }

    /**
     * @param Product[] $products
     */
    public function shouldGetAllProducts(array $products): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->willReturn($products);
    }
}

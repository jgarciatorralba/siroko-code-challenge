<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\TestCase;

use App\Products\Domain\Product;
use App\Products\Domain\Service\UpdateProduct;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class UpdateProductMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return UpdateProduct::class;
    }

    public function shouldUpdateProduct(Product $product): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($product);
    }
}

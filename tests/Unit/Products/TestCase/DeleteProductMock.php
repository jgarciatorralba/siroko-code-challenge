<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\TestCase;

use App\Products\Domain\Product;
use App\Products\Domain\Service\DeleteProduct;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class DeleteProductMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return DeleteProduct::class;
    }

    public function shouldDeleteProduct(Product $product): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($product);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\TestCase;

use App\Products\Domain\Product;
use App\Products\Domain\Service\CreateProduct;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class CreateProductMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return CreateProduct::class;
    }

    public function shouldCreateProduct(Product $product): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($product);
    }
}

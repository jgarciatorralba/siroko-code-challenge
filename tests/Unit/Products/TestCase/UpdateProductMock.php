<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\TestCase;

use App\Products\Domain\Product;
use App\Products\Domain\Service\UpdateProduct;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use DateTimeImmutable;

final class UpdateProductMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return UpdateProduct::class;
    }

    /**
     * @param array <string, string|float|DateTimeImmutable|null> $updatedData
     */
    public function shouldUpdateProduct(Product $product, array $updatedData): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($product, $updatedData);
    }
}

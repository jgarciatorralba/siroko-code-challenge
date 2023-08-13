<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\TestCase;

use App\Products\Domain\Exception\ProductNotFoundException;
use App\Products\Domain\Product;
use App\Products\Domain\Service\GetMappedProductsById;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class GetMappedProductsByIdMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return GetMappedProductsById::class;
    }

    /**
     * @param Uuid[] $ids
     * @param array<string, Product> $mappedProducts
     */
    public function shouldReturnMappedProducts(array $ids, array $mappedProducts): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($ids)
            ->willReturn($mappedProducts);
    }

    /**
     * @param Uuid[] $ids
     */
    public function shouldThrowException(array $ids): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($ids)
            ->willThrowException(new ProductNotFoundException(reset($ids)));
    }
}

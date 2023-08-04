<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\TestCase;

use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use App\Products\Domain\Contract\ProductRepository;
use App\Products\Domain\Product;

final class ProductRepositoryMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return ProductRepository::class;
    }

    /**
     * @param Product[] $products
     */
    public function shouldFindProducts(array $products): void
    {
        $this->mock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($products);
    }

    public function shouldNotFindProducts(): void
    {
        $this->mock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
    }

    public function shouldFindProductById(Uuid $id, Product $product): void
    {
        $this->mock
            ->expects($this->once())
            ->method('findOneById')
            ->with($id)
            ->willReturn($product);
    }

    public function shouldNotFindProductById(Uuid $id): void
    {
        $this->mock
            ->expects($this->once())
            ->method('findOneById')
            ->with($id)
            ->willReturn(null);
    }

    public function shouldCreateProduct(Product $product): void
    {
        $this->mock
            ->expects($this->once())
            ->method('create')
            ->with($product);
    }

    public function shouldUpdateProduct(Product $product): void
    {
        $this->mock
            ->expects($this->once())
            ->method('update')
            ->with($product);
    }

    public function shouldDeleteProduct(Product $product): void
    {
        $this->mock
            ->expects($this->once())
            ->method('delete')
            ->with($product);
    }
}

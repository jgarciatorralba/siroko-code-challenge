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

    public function shouldCreate(Product $product): void
    {
        $this->mock
            ->expects($this->once())
            ->method('create')
            ->with($product);
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
}

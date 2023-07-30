<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\TestCase;

use App\Products\Domain\Product;
use App\Products\Domain\Service\GetProductById;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class GetProductByIdMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return GetProductById::class;
    }

    public function shouldReturnProduct(Uuid $id, Product $product): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($id)
            ->willReturn($product);
    }
}

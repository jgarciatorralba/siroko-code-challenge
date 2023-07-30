<?php

declare(strict_types=1);

use App\Products\Application\Command\CreateProduct\CreateProductCommandHandler;
use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Products\Application\Command\CreateProduct\CreateProductCommandMother;
use App\Tests\Unit\Products\TestCase\CreateProductMock;

beforeEach(function () {
    $this->createProductMock = new CreateProductMock($this);
});

it('should create a product', function () {
    $now = new DateTimeImmutable();
    $product = Product::create(
        Uuid::random(),
        'test-product-create',
        10.10,
        $now,
        $now
    );
    $command = CreateProductCommandMother::createFromProduct($product);

    $this->createProductMock->shouldCreateProduct($product);

    $handler = new CreateProductCommandHandler(
        createProduct: $this->createProductMock->getMock()
    );
    $handler->__invoke($command);
});

<?php

declare(strict_types=1);

use App\Products\Application\Command\CreateProduct\CreateProductCommandHandler;
use App\Tests\Unit\Products\Application\Command\CreateProduct\CreateProductCommandMother;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Products\TestCase\CreateProductMock;

beforeEach(function () {
    $this->createProductMock = new CreateProductMock($this);
});

it('should create a product', function () {
    $product = ProductMother::create();
    $command = CreateProductCommandMother::createFromProduct($product);

    $this->createProductMock->shouldCreateProduct($product);

    $handler = new CreateProductCommandHandler(
        createProduct: $this->createProductMock->getMock()
    );
    $handler->__invoke($command);
});

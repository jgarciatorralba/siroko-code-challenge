<?php

declare(strict_types=1);

use App\Products\Domain\Exception\ProductInUseException;
use App\Products\Domain\Service\DeleteProduct;
use App\Tests\Unit\Carts\Domain\CartItemMother;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Products\TestCase\ProductRepositoryMock;

beforeEach(function () {
    $this->productRepositoryMock = new ProductRepositoryMock($this);
});

it('should delete a product', function () {
    $product = ProductMother::create();

    $this->productRepositoryMock->shouldDeleteProduct($product);

    $service = new DeleteProduct(
        productRepository: $this->productRepositoryMock->getMock()
    );
    $result = $service->__invoke($product);

    expect($result)->toBeEmpty();
});

it('should throw an exception if a product is referenced by a cart item', function () {
    $product = ProductMother::create();
    $cart = CartMother::create();
    $cartItem = CartItemMother::create(null, $cart, $product, null, null, null, null);

    $product->addCartItem($cartItem);

    $this->productRepositoryMock->shouldNotDeleteProduct($product);

    $service = new DeleteProduct(
        productRepository: $this->productRepositoryMock->getMock()
    );
    $service->__invoke($product);
})->throws(ProductInUseException::class);

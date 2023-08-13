<?php

declare(strict_types=1);

use App\Carts\Application\Command\CreateCart\CreateCartCommandHandler;
use App\Products\Domain\Exception\ProductNotFoundException;
use App\Tests\Unit\Carts\Application\Command\CreateCart\CreateCartCommandMother;
use App\Tests\Unit\Carts\Domain\CartItemMother;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Carts\TestCase\CreateCartMock;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Products\TestCase\GetMappedProductsByIdMock;

beforeEach(function () {
    $this->getMappedProductsByIdMock = new GetMappedProductsByIdMock($this);
    $this->createCartMock = new CreateCartMock($this);
});

it('should create a cart', function () {
    $cart = CartMother::create(null, null, false, null, null);
    $product = ProductMother::create();
    $cartItem = CartItemMother::create(null, $cart, $product, null, null, $cart->createdAt(), $cart->updatedAt());

    $cart->addItem($cartItem);

    $command = CreateCartCommandMother::createFromCart($cart);

    $this->getMappedProductsByIdMock->shouldReturnMappedProducts(
        [$product->id()],
        [$product->id()->value() => $product]
    );
    $this->createCartMock->shouldCreateCart($cart);

    $handler = new CreateCartCommandHandler(
        getMappedProductsById: $this->getMappedProductsByIdMock->getMock(),
        createCart: $this->createCartMock->getMock()
    );
    $handler->__invoke($command);
});

it('should throw an exception if a product is not found', function () {
    $cart = CartMother::create(null, null, false, null, null);
    $product = ProductMother::create();
    $cartItem = CartItemMother::create(null, $cart, $product, null, null, null, null);

    $cart->addItem($cartItem);

    $command = CreateCartCommandMother::createFromCart($cart);

    $this->getMappedProductsByIdMock->shouldThrowException(
        [$product->id()]
    );
    $this->createCartMock->shouldNotCreateCart($cart);

    $handler = new CreateCartCommandHandler(
        getMappedProductsById: $this->getMappedProductsByIdMock->getMock(),
        createCart: $this->createCartMock->getMock()
    );
    $handler->__invoke($command);
})->throws(ProductNotFoundException::class);

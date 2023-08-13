<?php

declare(strict_types=1);

use App\Carts\Domain\Exception\CartAlreadyConfirmedException;
use App\Carts\Domain\Exception\EmptyCartException;
use App\Carts\Domain\Service\ConfirmCart;
use App\Tests\Unit\Carts\Domain\CartItemMother;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Carts\TestCase\CartRepositoryMock;
use App\Tests\Unit\Products\Domain\ProductMother;

beforeEach(function () {
    $this->cartRepositoryMock = new CartRepositoryMock($this);
});

it('should confirm a cart', function () {
    $cart = CartMother::create(null, null, false, null, null);
    $product = ProductMother::create();
    $cartItem = CartItemMother::create(null, $cart, $product, null, null, null, null);

    $cart->addItem($cartItem);

    $this->cartRepositoryMock->shouldUpdateCart($cart);

    $service = new ConfirmCart(
        cartRepository: $this->cartRepositoryMock->getMock()
    );
    $result = $service->__invoke($cart);

    expect($result)->toBeEmpty();
});

it('should throw an exception if a cart is already confirmed', function () {
    $cart = CartMother::create(null, null, true, null, null);
    $product = ProductMother::create();
    $cartItem = CartItemMother::create(null, $cart, $product, null, null, null, null);

    $cart->addItem($cartItem);

    $this->cartRepositoryMock->shouldNotUpdateCart($cart);

    $service = new ConfirmCart(
        cartRepository: $this->cartRepositoryMock->getMock()
    );
    $service->__invoke($cart);
})->throws(CartAlreadyConfirmedException::class);

it('should throw an exception if a cart is empty', function () {
    $cart = CartMother::create(null, null, false, null, null);

    $this->cartRepositoryMock->shouldNotUpdateCart($cart);

    $service = new ConfirmCart(
        cartRepository: $this->cartRepositoryMock->getMock()
    );
    $service->__invoke($cart);
})->throws(EmptyCartException::class);

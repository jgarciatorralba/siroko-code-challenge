<?php

declare(strict_types=1);

use App\Carts\Application\Query\GetCartById\GetCartByIdQueryHandler;
use App\Carts\Domain\Exception\CartNotFoundException;
use App\Tests\Unit\Carts\Application\Query\GetCartById\GetCartByIdQueryMother;
use App\Tests\Unit\Carts\Application\Query\GetCartById\GetCartByIdResponseMother;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Carts\TestCase\GetCartByIdMock;

beforeEach(function () {
    $this->getCartById = new GetCartByIdMock($this);
});

it('should return a normalized cart given its id', function () {
    $cart = CartMother::create(null, null, false, null, null);

    $query = GetCartByIdQueryMother::create(
        id: $cart->id()->value()
    );
    $response = GetCartByIdResponseMother::create($cart);

    $this->getCartById->shouldReturnCart($cart->id(), $cart);

    $handler = new GetCartByIdQueryHandler(
        getCartById: $this->getCartById->getMock()
    );
    $result = $handler->__invoke($query);

    expect($result)->toBeQueryResponse($response->data());
});

it('should throw an exception if a cart is not found', function () {
    $cart = CartMother::create(null, null, false, null, null);

    $query = GetCartByIdQueryMother::create(
        id: $cart->id()->value()
    );
    $response = GetCartByIdResponseMother::create($cart);

    $this->getCartById->shouldThrowException($cart->id());

    $handler = new GetCartByIdQueryHandler(
        getCartById: $this->getCartById->getMock()
    );
    $result = $handler->__invoke($query);

    expect($result)->toBeQueryResponse($response->data());
})->throws(CartNotFoundException::class);

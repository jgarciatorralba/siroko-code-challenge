<?php

declare(strict_types=1);

use App\Carts\Domain\Exception\CartNotFoundException;
use App\Carts\Domain\Service\GetCartById;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Carts\TestCase\CartRepositoryMock;

beforeEach(function () {
    $this->cartRepositoryMock = new CartRepositoryMock($this);
});

it('should return a cart', function () {
    $id = Uuid::random();
    $cart = CartMother::create($id);

    $this->cartRepositoryMock->shouldFindCartById($id, $cart);

    $service = new GetCartById(
        cartRepository: $this->cartRepositoryMock->getMock()
    );
    $result = $service->__invoke($id);

    expect($result)->toEqual($cart);
});

it('should throw an exception if a cart is not found', function () {
    $id = Uuid::random();

    $this->cartRepositoryMock->shouldNotFindCartById($id);

    $service = new GetCartById(
        cartRepository: $this->cartRepositoryMock->getMock()
    );
    $service->__invoke($id);
})->throws(CartNotFoundException::class);

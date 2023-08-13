<?php

declare(strict_types=1);

use App\Carts\Domain\Service\CreateCart;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Carts\TestCase\CartRepositoryMock;

beforeEach(function () {
    $this->cartRepositoryMock = new CartRepositoryMock($this);
});

it('should create a cart', function () {
    $cart = CartMother::create(null, null, false, null, null);

    $this->cartRepositoryMock->shouldCreateCart($cart);

    $service = new CreateCart(
        cartRepository: $this->cartRepositoryMock->getMock()
    );
    $result = $service->__invoke($cart);

    expect($result)->toEqual(null);
});

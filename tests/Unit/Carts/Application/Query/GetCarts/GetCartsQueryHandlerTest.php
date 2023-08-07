<?php

declare(strict_types=1);

use App\Carts\Application\Query\GetCarts\GetCartsQuery;
use App\Carts\Application\Query\GetCarts\GetCartsQueryHandler;
use App\Carts\Domain\Cart;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Carts\Application\Query\GetCarts\GetCartsResponseMother;
use App\Tests\Unit\Carts\TestCase\GetCartsMock;

beforeEach(function () {
    $this->getCartsMock = new GetCartsMock($this);
});

it('should return an array of normalized carts', function () {
    $carts = [
        Cart::create(
            Uuid::random(),
            new DateTimeImmutable(),
            new DateTimeImmutable()
        ),
        Cart::create(
            Uuid::random(),
            new DateTimeImmutable(),
            new DateTimeImmutable()
        ),
        Cart::create(
            Uuid::random(),
            new DateTimeImmutable(),
            new DateTimeImmutable()
        )
    ];

    $this->getCartsMock->shouldGetAllCarts($carts);

    $handler = new GetCartsQueryHandler(
        getCarts: $this->getCartsMock->getMock()
    );
    $result = $handler->__invoke(new GetCartsQuery());

    $response = GetCartsResponseMother::create($carts);
    expect($result)->toBeQueryResponse($response->data());
});

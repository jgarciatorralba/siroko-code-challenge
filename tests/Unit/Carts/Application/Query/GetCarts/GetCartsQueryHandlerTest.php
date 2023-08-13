<?php

declare(strict_types=1);

use App\Carts\Application\Query\GetCarts\GetCartsQuery;
use App\Carts\Application\Query\GetCarts\GetCartsQueryHandler;
use App\Tests\Unit\Carts\Application\Query\GetCarts\GetCartsResponseMother;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Carts\TestCase\GetCartsMock;

beforeEach(function () {
    $this->getCartsMock = new GetCartsMock($this);
});

it('should return an array of normalized carts', function () {
    $carts = [
        CartMother::create(),
        CartMother::create(),
        CartMother::create()
    ];

    $this->getCartsMock->shouldGetAllCarts($carts);

    $handler = new GetCartsQueryHandler(
        getCarts: $this->getCartsMock->getMock()
    );
    $result = $handler->__invoke(new GetCartsQuery());

    $response = GetCartsResponseMother::create($carts);
    expect($result)->toBeQueryResponse($response->data());
});

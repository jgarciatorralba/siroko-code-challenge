<?php

declare(strict_types=1);

use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Carts\Domain\CartItemMother;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Products\Domain\ProductMother;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $testProduct = ProductMother::create(null, 'test-product-delete', null, null, null);

    $this->persist($testProduct);
});

afterEach(function () {
    $this->clearDatabase();
});

describe('DeleteProductByIdController', function () {
    it('should delete a product given its id', function () {
        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-product-delete']);
        $id = $testProduct->id()->value();

        $client = $this->getApiClient();
        $response = $client->request('DELETE', "/api/products/$id");

        expect($response->getStatusCode())->toEqual(Response::HTTP_NO_CONTENT)
            ->and($response->getContent())->toBeEmpty();
    });

    it('should throw an exception if a product is not found', function () {
        $id = Uuid::random()->value();

        $client = $this->getApiClient();
        $response = $client->request('DELETE', "/api/products/$id");
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_NOT_FOUND)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'product_not_found',
                'error' => "Product with id '$id' could not be found."
            ]);
    })->throws(ClientException::class);

    it('should throw an exception if a product is referenced by a cart item', function () {
        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-product-delete']);
        $id = $testProduct->id()->value();

        $testCart = CartMother::create();
        $this->persist($testCart);

        $testCartItem = CartItemMother::create(
            null,
            $testCart,
            $testProduct,
            1,
            null,
            null,
            null
        );
        $this->persist($testCartItem);

        $client = $this->getApiClient();
        $response = $client->request('DELETE', "/api/products/$id");
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_CONFLICT)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'product_in_use',
                'error' => "Product with id '$id' is being referenced by cart items."
            ]);
    })->throws(ClientException::class);
});

<?php

declare(strict_types=1);

use App\Products\Domain\Product;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $testProduct = ProductMother::create(null, 'test-cart-create', null, null, null);

    $this->persist($testProduct);
});

afterEach(function () {
    $this->clearDatabase();
});

describe('CreateCartController', function () {
    it('should create a cart and return its id', function () {
        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-cart-create']);

        $client = $this->getApiClient();
        $response = $client->request('POST', '/api/carts', [
            'body' => json_encode([
                'items' => [
                    [
                        'productId' => $testProduct->id()->value(),
                        'quantity' => FakeValueGenerator::integer(1)
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_CREATED)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toHaveCount(1)
            ->and($decodedResponse)->toHaveKey('id');
    });

    it('should throw a validation error when the items are empty', function () {
        $client = $this->getApiClient();
        $response = $client->request('POST', '/api/carts', [
            'body' => json_encode([
                'items' => []
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_PRECONDITION_FAILED)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toHaveCount(1)
            ->and($decodedResponse)->toBe([
                'code' => 'validation_exception',
                'error' => 'Invalid request data.',
                'errors' => [
                    'items' => 'This collection should contain 1 element or more.'
                ]
            ]);
    })->throws(ClientException::class);

    it('should throw a validation error when the product id is missing', function () {
        $client = $this->getApiClient();
        $response = $client->request('POST', '/api/carts', [
            'body' => json_encode([
                'items' => [
                    [
                        'quantity' => FakeValueGenerator::integer(1)
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_PRECONDITION_FAILED)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toHaveCount(1)
            ->and($decodedResponse)->toBe([
                'code' => 'validation_exception',
                'error' => 'Invalid request data.',
                'errors' => [
                    'productId' => 'This field is missing.'
                ]
            ]);
    })->throws(ClientException::class);

    it('should throw an exception if a product is not found', function () {
        $id = FakeValueGenerator::uuid()->value();

        $client = $this->getApiClient();
        $response = $client->request('POST', '/api/carts', [
            'body' => json_encode([
                'items' => [
                    [
                        'productId' => $id,
                        'quantity' => FakeValueGenerator::integer(1)
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_NOT_FOUND)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'product_not_found',
                'error' => "Product with id '$id' could not be found."
            ]);
    })->throws(ClientException::class);

    it('should throw a validation error when the quantity is missing', function () {
        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-cart-create']);

        $client = $this->getApiClient();
        $response = $client->request('POST', '/api/carts', [
            'body' => json_encode([
                'items' => [
                    [
                        'productId' => $testProduct->id()->value()
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_PRECONDITION_FAILED)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toHaveCount(1)
            ->and($decodedResponse)->toBe([
                'code' => 'validation_exception',
                'error' => 'Invalid request data.',
                'errors' => [
                    'quantity' => 'This field is missing.'
                ]
            ]);
    })->throws(ClientException::class);

    it('should throw a validation error when the quantity is not a positive integer', function () {
        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-cart-create']);

        $client = $this->getApiClient();
        $response = $client->request('POST', '/api/carts', [
            'body' => json_encode([
                'items' => [
                    [
                        'productId' => $testProduct->id()->value(),
                        'quantity' => FakeValueGenerator::string()
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_PRECONDITION_FAILED)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toHaveCount(1)
            ->and($decodedResponse)->toBe([
                'code' => 'validation_exception',
                'error' => 'Invalid request data.',
                'errors' => [
                    'quantity' => 'This value should be of type int.'
                ]
            ]);
    })->throws(ClientException::class);
});

<?php

declare(strict_types=1);

use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $testProduct = ProductMother::create(null, 'test-product-update', null, null, null);

    $this->persist($testProduct);
});

afterEach(function () {
    $this->clearDatabase();
});

describe('UpdateProductController', function () {
    it('should update a product', function () {
        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-product-update']);
        $id = $testProduct->id()->value();

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/products', [
            'body' => json_encode([
                'id' => $id,
                'name' => FakeValueGenerator::string(),
                'price' => FakeValueGenerator::float(1, 100)
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_OK)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeEmpty();
    });

    it('should throw an exception if a product is not found', function () {
        $id = Uuid::random()->value();

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/products', [
            'body' => json_encode([
                'id' => $id,
                'name' => FakeValueGenerator::string(),
                'price' => FakeValueGenerator::float(1, 100)
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

    it('should throw a validation error when the name is empty', function () {
        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-product-update']);
        $id = $testProduct->id()->value();

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/products', [
            'body' => json_encode([
                'id' => $id,
                'name' => '',
                'price' => FakeValueGenerator::float(1, 100)
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
                    'name' => 'This value should not be blank.'
                ]
            ]);
    })->throws(ClientException::class);

    it('should throw a validation error when the price is not numeric', function () {
        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-product-update']);
        $id = $testProduct->id()->value();

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/products', [
            'body' => json_encode([
                'id' => $id,
                'name' => FakeValueGenerator::string(),
                'price' => FakeValueGenerator::string()
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
                    'name' => 'This value should be of type numeric.'
                ]
            ]);
    })->throws(ClientException::class);

    it('should throw a validation error when the price is not positive', function () {
        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-product-update']);
        $id = $testProduct->id()->value();

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/products', [
            'body' => json_encode([
                'id' => $id,
                'name' => FakeValueGenerator::string(),
                'price' => 0
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
                    'name' => 'This value should be positive.'
                ]
            ]);
    })->throws(ClientException::class);
});

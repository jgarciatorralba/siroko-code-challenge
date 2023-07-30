<?php

declare(strict_types=1);

use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

it('should create a product and return its id', function () {
    $client = $this->getApiClient();
    $response = $client->request('POST', '/api/products', [
        'body' => json_encode([
            'name' => 'test-product-create',
            'price' => 11.11
        ])
    ]);
    $decodedResponse = $response->toArray();

    expect($response->getStatusCode())->toEqual(Response::HTTP_CREATED)
        ->and($response->getContent())->toBeJson()
        ->and($decodedResponse)->toBeArray()
        ->and($decodedResponse)->toHaveCount(1)
        ->and($decodedResponse)->toHaveKey('id');
});

it('should throw a validation error when the name is empty', function () {
    $client = $this->getApiClient();
    $response = $client->request('POST', '/api/products', [
        'body' => json_encode([
            'name' => '',
            'price' => 11.11
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

it('should throw a validation error when price is missing', function () {
    $client = $this->getApiClient();
    $response = $client->request('POST', '/api/products', [
        'body' => json_encode([
            'name' => 'test-product-create'
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
                'price' => 'This field is missing.'
            ]
        ]);
})->throws(ClientException::class);

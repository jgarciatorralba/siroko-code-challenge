<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;

describe('GetProductsController', function () {
    it('should return a list of serialized products', function () {
        $client = $this->getApiClient();
        $response = $client->request('GET', '/api/products');
        $decodedResponse = $response->toArray();
        $length = count($decodedResponse);

        expect($response->getStatusCode())->toEqual(Response::HTTP_OK)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse[rand(0, $length - 1)])
                ->toHaveKeys(['id', 'name', 'price', 'created_at', 'updated_at'])
            ->and($decodedResponse[rand(0, $length - 1)]['id'])->toBeString()
            ->and($decodedResponse[rand(0, $length - 1)]['name'])->toBeString()
            ->and(floatval($decodedResponse[rand(0, $length - 1)]['price']))->toBeFloat()
            ->and(new DateTimeImmutable($decodedResponse[rand(0, $length - 1)]['created_at']))
                ->toBeInstanceOf(DateTimeImmutable::class)
            ->and(new DateTimeImmutable($decodedResponse[rand(0, $length - 1)]['updated_at']))
                ->toBeInstanceOf(DateTimeImmutable::class);
    });
});

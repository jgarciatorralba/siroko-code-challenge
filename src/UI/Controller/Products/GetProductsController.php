<?php

declare(strict_types=1);

namespace App\UI\Controller\Products;

use App\Products\Application\Query\GetProducts\GetProductsQuery;
use App\UI\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetProductsController extends BaseController
{
    public function __invoke(): Response
    {
        $response = $this->ask(new GetProductsQuery());
        return new JsonResponse($response->data(), Response::HTTP_OK);
    }
}

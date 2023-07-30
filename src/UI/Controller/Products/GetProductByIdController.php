<?php

declare(strict_types=1);

namespace App\UI\Controller\Products;

use App\UI\Controller\BaseController;
use App\Products\Application\Query\GetProductById\GetProductByIdQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetProductByIdController extends BaseController
{
    public function __invoke(string $id): Response
    {
        $response = $this->ask(new GetProductByIdQuery($id));

        return new JsonResponse($response->data(), Response::HTTP_OK);
    }
}

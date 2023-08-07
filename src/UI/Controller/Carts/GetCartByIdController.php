<?php

declare(strict_types=1);

namespace App\UI\Controller\Carts;

use App\UI\Controller\BaseController;
use App\Carts\Application\Query\GetCartById\GetCartByIdQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetCartByIdController extends BaseController
{
    public function __invoke(string $id): Response
    {
        $response = $this->ask(new GetCartByIdQuery($id));

        return new JsonResponse($response->data(), Response::HTTP_OK);
    }
}

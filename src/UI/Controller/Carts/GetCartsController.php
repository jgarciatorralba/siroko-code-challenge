<?php

declare(strict_types=1);

namespace App\UI\Controller\Carts;

use App\Carts\Application\Query\GetCarts\GetCartsQuery;
use App\UI\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetCartsController extends BaseController
{
    public function __invoke(): Response
    {
        $response = $this->ask(new GetCartsQuery());
        return new JsonResponse($response->data(), Response::HTTP_OK);
    }
}

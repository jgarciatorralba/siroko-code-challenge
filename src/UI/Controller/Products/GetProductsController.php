<?php

declare(strict_types=1);

namespace App\UI\Controller\Products;

use App\UI\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetProductsController extends BaseController
{
    public function __invoke(): Response
    {
        return new JsonResponse(['Hello World!'], Response::HTTP_OK);
    }
}

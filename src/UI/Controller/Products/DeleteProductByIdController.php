<?php

declare(strict_types=1);

namespace App\UI\Controller\Products;

use App\UI\Controller\BaseController;
use App\Products\Application\Command\DeleteProductById\DeleteProductByIdCommand;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DeleteProductByIdController extends BaseController
{
    public function __invoke(string $id): Response
    {
        $this->dispatch(new DeleteProductByIdCommand(id: $id));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}

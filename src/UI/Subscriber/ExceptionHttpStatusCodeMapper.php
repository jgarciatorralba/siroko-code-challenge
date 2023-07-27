<?php

declare(strict_types=1);

namespace App\UI\Subscriber;

use App\Products\Domain\Exception\ProductNotFoundException;
use Symfony\Component\HttpFoundation\Response;

final class ExceptionHttpStatusCodeMapper
{
    private const EXCEPTIONS = [
        // Products
        ProductNotFoundException::class => Response::HTTP_NOT_FOUND,

        // Orders
    ];

    public function getStatusCodeFor(string $exceptionClass): ?int
    {
        return self::EXCEPTIONS[$exceptionClass] ?? null;
    }
}

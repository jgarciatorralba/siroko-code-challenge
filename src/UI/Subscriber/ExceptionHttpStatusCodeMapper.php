<?php

declare(strict_types=1);

namespace App\UI\Subscriber;

use App\Carts\Domain\Exception\CartAlreadyConfirmedException;
use App\Carts\Domain\Exception\CartItemAlreadyExistingException;
use App\Carts\Domain\Exception\CartItemNotFoundException;
use App\Carts\Domain\Exception\CartNotFoundException;
use App\Carts\Domain\Exception\EmptyCartException;
use App\Products\Domain\Exception\ProductInUseException;
use App\Products\Domain\Exception\ProductNotFoundException;
use Symfony\Component\HttpFoundation\Response;

final class ExceptionHttpStatusCodeMapper
{
    private const EXCEPTIONS = [
        // Products
        ProductNotFoundException::class => Response::HTTP_NOT_FOUND,
        ProductInUseException::class => Response::HTTP_CONFLICT,

        // Carts
        CartNotFoundException::class => Response::HTTP_NOT_FOUND,
        CartAlreadyConfirmedException::class => Response::HTTP_UNPROCESSABLE_ENTITY,
        EmptyCartException::class => Response::HTTP_BAD_REQUEST,
        CartItemNotFoundException::class => Response::HTTP_NOT_FOUND,
        CartItemAlreadyExistingException::class => Response::HTTP_CONFLICT
    ];

    public function getStatusCodeFor(string $exceptionClass): ?int
    {
        return self::EXCEPTIONS[$exceptionClass] ?? null;
    }
}

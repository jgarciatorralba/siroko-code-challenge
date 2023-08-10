<?php

declare(strict_types=1);

namespace App\UI\Request\Carts;

use App\UI\Request\AbstractRequest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class CreateCartRequest extends AbstractRequest
{
    protected function validationRules(): Assert\Collection
    {
        return new Assert\Collection([
            'items' => new Assert\Required([
                new Assert\Type('array'),
                new Assert\Count(['min' => 1]),
                new Assert\All([
                    new Assert\Collection([
                        'productId' => [
                            new Assert\NotBlank(),
                            new Assert\Type('string'),
                            new Assert\Length(36),
                            new Assert\Callback(['callback' => [$this, 'validateUniqueId']])
                        ],
                        'quantity' => [
                            new Assert\NotBlank(),
                            new Assert\Type('int'),
                            new Assert\Positive()
                        ],
                    ])
                ]),
            ])
        ]);
    }

    public function validateUniqueId(string $productId, ExecutionContextInterface $context): void
    {
        $items = $this->payload()['items'];

        if (array_count_values(array_column($items, 'productId'))[$productId] > 1) {
            $context->buildViolation("This value cannot be used twice.")
                ->addViolation();
        }
    }
}

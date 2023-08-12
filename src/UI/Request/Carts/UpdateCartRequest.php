<?php

declare(strict_types=1);

namespace App\UI\Request\Carts;

use App\UI\Request\AbstractRequest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class UpdateCartRequest extends AbstractRequest
{
    protected function validationRules(): Assert\Collection
    {
        return new Assert\Collection([
            'id' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Length(36),
            ]),
            'operations' => new Assert\Required([
                new Assert\Type('array'),
                new Assert\Count(['min' => 1]),
                new Assert\All([
                    new Assert\Collection([
                        'operation' => new Assert\Required([
                            new Assert\NotBlank(),
                            new Assert\Type('string'),
                            new Assert\Choice(['add', 'update', 'remove'])
                        ]),
                        'productId' => new Assert\Required([
                            new Assert\NotBlank(),
                            new Assert\Type('string'),
                            new Assert\Length(36),
                            new Assert\Callback(['callback' => [$this, 'validateUniqueId']])
                        ]),
                        'quantity' => new Assert\Optional([
                            new Assert\NotBlank(),
                            new Assert\Type('int'),
                            new Assert\Positive()
                        ])
                    ])
                ]),
                new Assert\Callback(['callback' => [$this, 'validateQuantity']])
            ])
        ]);
    }

    public function validateUniqueId(string $productId, ExecutionContextInterface $context): void
    {
        $operations = $this->payload()['operations'];

        if (array_count_values(array_column($operations, 'productId'))[$productId] > 1) {
            $context->buildViolation("This value cannot be used twice.")
                ->addViolation();
        }
    }

    /**
     * @param array<array<string, string|int>> $operations
     */
    public function validateQuantity(array $operations, ExecutionContextInterface $context): void
    {
        foreach ($operations as $operation) {
            if (
                isset($operation['operation']) &&
                in_array($operation['operation'], ['add', 'update']) &&
                empty($operation['quantity'])
            ) {
                $context->buildViolation("Quantity is required for 'add' or 'update' operations.")
                    ->addViolation();
            }
        }
    }
}

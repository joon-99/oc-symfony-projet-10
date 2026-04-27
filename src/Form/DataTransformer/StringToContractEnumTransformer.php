<?php

namespace App\Form\DataTransformer;

use App\Enum\ContractEnum;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

final class StringToContractEnumTransformer implements DataTransformerInterface
{
    /**
     * Transforms a ContractEnum (or null) to a string for the form field.
     *
     * @param ContractEnum|null $value
     * @return string|null
     */
    public function transform(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof ContractEnum) {
            throw new TransformationFailedException('Expected a ContractEnum.');
        }

        return $value->value;
    }

    /**
     * Transforms a string (submitted value) to a ContractEnum or null.
     *
     * @param string|null $value
     * @return ContractEnum|null
     */
    public function reverseTransform(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        $enum = ContractEnum::tryFrom($value);

        if ($enum === null) {
            throw new TransformationFailedException(sprintf('Invalid contract value "%s".', $value));
        }

        return $enum;
    }
}

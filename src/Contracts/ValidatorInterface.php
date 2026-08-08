<?php

declare(strict_types=1);

namespace WPAIOS\Contracts;

/**
 * Data Validator Interface contract.
 */
interface ValidatorInterface
{
    /**
     * Validate an array of data against validation rules.
     *
     * @param array<string, mixed>                $data
     * @param array<string, string|array<string>> $rules
     * @return bool
     */
    public function validate(array $data, array $rules): bool;

    /**
     * Get validation error messages.
     *
     * @return array<string, array<string>>
     */
    public function errors(): array;
}

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Services\SourceValidationService;

class ValidSourceRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return app(SourceValidationService::class)->isValidSource($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The selected source is invalid.';
    }
}

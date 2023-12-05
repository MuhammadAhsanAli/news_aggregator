<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

/**
 * Class NewsValidator
 *
 * Service class for validating news article data.
 */
class NewsValidator
{
    /**
     * Validate article data against defined rules.
     *
     * @param array $data  The data to be validated.
     * @param array $rules The validation rules.
     *
     * @return bool Whether the validation passes.
     */
    public function validate(array $data, array $rules): bool
    {
        $validator = Validator::make($data, $rules);

        return !$validator->fails();
    }
}

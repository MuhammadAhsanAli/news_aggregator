<?php

namespace App\Services;

use App\Models\Source;

class SourceValidationService
{
    /**
     * Check if the given source is valid.
     *
     * @param string $source
     * @return bool
     */
    public function isValidSource(string $source): bool
    {
        return Source::where('source', $source)->exists();
    }
}

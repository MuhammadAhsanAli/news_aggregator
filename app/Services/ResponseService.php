<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class ResponseService
{
    /**
     * Respond with JSON data.
     *
     * @param mixed $data
     * @param int $status
     * @return JsonResponse
     */
    public function jsonResponse(mixed $data, int $status = 200): JsonResponse
    {
        return response()->json($data, $status);
    }
}

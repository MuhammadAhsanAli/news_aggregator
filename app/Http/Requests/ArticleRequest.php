<?php

namespace App\Http\Requests;

use App\Rules\ValidSourceRule;
use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'fromDate' => 'nullable|date_format:Y-m-d',
            'toDate' => 'nullable|date_format:Y-m-d',
            'author' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'source' => ['nullable', 'string', 'max:255', new ValidSourceRule],
        ];
    }
}

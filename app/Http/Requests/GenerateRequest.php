<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ingredients' => 'required|array|min:3',
            'ingredients.*' => 'string|max:50',
            'cuisine' => 'required|string|max:50',
            'diet' => 'required|string|max:50',
            'servings' => 'required|int|min:1|max:20',
            'minutes' => 'required|int|min:0|max:60',
        ];
    }
}

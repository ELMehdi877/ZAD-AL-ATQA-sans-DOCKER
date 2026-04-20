<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEvaluationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'du_sourate' => 'nullable|string|max:255',
            'au_sourate' => 'nullable|string|max:255',
            'hizb' => 'nullable|integer|min:1|max:60',
            'du_aya' => 'nullable|integer|min:1|max:286',
            'au_aya' => 'nullable|integer|min:1|max:286',
            'note' => 'nullable|numeric|min:0|max:20',
            'presence' => 'required|in:present,absent,retard',
            'remarque' => 'nullable|string',
        ];
    }
}

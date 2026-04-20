<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHalaqaRequest extends FormRequest
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
                'nom_halaqa' => 'required|string|min:5|max:50',
                'capacite' => 'required|integer',
                'cheikh_id' => 'required|exists:users,id',
                'students' => 'nullable|array',
                'students.*' => 'exists:students,id',
            ];
    }
}

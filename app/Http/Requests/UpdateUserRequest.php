<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        // Récupère l'ID de l'utilisateur à mettre à jour
        $userId = $this->route('user')->id ?? null;

        return [
            'nom' => 'required|string|min:3|max:20',
            'prenom' => 'required|string|min:3|max:20',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => 'nullable|confirmed|min:8', // nullable si pas de changement
            'role' => 'required|in:admin,student,parent,cheikh',
            'parent_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', 'parent'),
            ],
            'telephone' => 'nullable|string|max:15',
            'nombre_hifz' => 'nullable|integer|min:0|max:60',
        ];
    }
}

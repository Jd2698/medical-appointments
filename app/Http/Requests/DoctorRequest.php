<?php

namespace App\Http\Requests;

use App\Enums\RolesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DoctorRequest extends UserRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        // Modify rule
        $this->rules['specialty_id'] = ['required', 'exists:specialties,id'];
        $this->rules['role'] = ['nullable'];

        if ($this->method() == 'PUT') {
            $this->rules['password'] = ['nullable', 'confirmed'];
            $this->rules['documento_identidad'] = [
                'required',
                'numeric', 'min_digits:8', 'max_digits:10',
                'unique:users,documento_identidad,' . $this->doctor->user_id
            ];
        }

        return $this->rules;
    }
}

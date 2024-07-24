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
        // Add rule
        $this->rules['specialty_id'] = ['required', 'exists:specialties,id'];

        if ($this->method() == 'POST') {
            $this->rules['role'] = ['nullable'];
        }

        if ($this->method() == 'PUT') {
            $this->rules['password'] = ['nullable', 'confirmed'];
        }

        return $this->rules;
    }
}

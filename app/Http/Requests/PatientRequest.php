<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PatientRequest extends UserRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        // Modify rule
        $this->rules['eps'] = ['required'];
        $this->rules['medical_history'] = ['nullable', 'max:50'];
        $this->rules['role'] = ['nullable'];

        if ($this->method() == 'PUT') {
            $this->rules['password'] = ['nullable', 'confirmed'];
            $this->rules['documento_identidad'] = [
                'required',
                'numeric', 'min_digits:8', 'max_digits:10',
                'unique:users,documento_identidad,' . $this->patient->user_id
            ];
        }

        return $this->rules;
    }
}

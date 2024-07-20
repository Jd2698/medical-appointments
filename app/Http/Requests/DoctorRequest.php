<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DoctorRequest extends UserRequest
{
    public function authorize(): bool
    {
        // return false;
        return Auth::check();
    }

    public function rules(): array
    {
        $this->rules['specialty'] = ['required'];
        $this->rules['role'] = ['nullable'];

        if ($this->method() == 'PUT') {
            $this->rules['password'] = ['nullable', 'confirmed'];
        }

        return $this->rules;
    }
}

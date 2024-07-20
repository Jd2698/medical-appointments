<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{

    protected $rules = [
        'name' => ['required'],
        'documento_identidad' => ['required', 'numeric'],
        'gender' => ['required', 'in:male,female'],
        'birthdate' => ['required', 'date'],
        'address' => ['required'],
        'phone' => ['required', 'numeric'],
        'email' => ['required', 'email'],
        'password' => ['required', 'confirmed'],
        'role' => ['required', 'in:admin,client']
    ];
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        if ($this->method() == 'PUT') {
            $this->rules['password'] = ['nullable', 'confirmed'];
        }

        return $this->rules;
    }
}

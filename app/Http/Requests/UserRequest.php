<?php

namespace App\Http\Requests;

use App\Enums\GendersEnum;
use App\Enums\RolesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

    protected $rules;

    public function __construct()
    {
        $this->rules =
            [
                'name' => ['required'],
                'documento_identidad' => ['required', 'numeric'],
                'gender' => ['required', Rule::in(GendersEnum::cases())],
                'birthdate' => ['required', 'date'],
                'address' => ['required'],
                'phone' => ['required', 'numeric'],
                'email' => ['required', 'email'],
                'password' => ['required', 'confirmed'],
                'role' => ['required', Rule::in(RolesEnum::cases())]
            ];
    }

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

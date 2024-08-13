<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SpecialtyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'max: 40', 'unique:specialties,name'],
            'is_active' => ['in:0,1']
        ];

        if ($this->method() == 'PUT') {
            $rules['name'] = ['required', 'max: 40', 'unique:specialties,name,' . $this->specialty->id];
        }

        return $rules;
    }
}

<?php

namespace App\Http\Requests;

use App\Enums\GendersEnum;
use App\Enums\RolesEnum;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

    protected $rules;

    public function __construct()
    {
        $maxDate = Carbon::now()->subYears(10)->toDateString();
        $this->rules =
            [
                'name' => [
                    'required', 'max: 40'
                ],
                'documento_identidad' => [
                    'required',
                    'numeric', 'min_digits:8', 'max_digits:10',
                    'unique:users,documento_identidad'
                ],
                'gender' => [
                    'required', Rule::in(GendersEnum::cases())
                ],
                'birthdate' => [
                    'required',
                    'date', 'before_or_equal:' . $maxDate
                ],
                'address' => ['required', 'max:30'],
                'phone' => [
                    'required',
                    'numeric', 'min_digits:10', 'max_digits:10'
                ],
                'email' => [
                    'required', 'email', 'max:60'
                ],
                'password' => [
                    'required', 'confirmed'
                ],
                'role' => ['required', 'array'],
                'role.*' => ['required', Rule::in(RolesEnum::cases())],
                'is_active' => ['in:0,1']
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
            $this->rules['documento_identidad'] =
                [
                    'required',
                    'numeric', 'min_digits:8', 'max_digits:10',
                    'unique:users,documento_identidad,' . $this->user->id
                ];
        }

        return $this->rules;
    }
}

<?php

namespace App\Http\Requests;

use App\Enums\AppointmentStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $rules = [
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|in:15,30,60',
            'comment' => 'nullable',
            'status' => Rule::in(AppointmentStatusEnum::cases()),
        ];

        if ($this->method() == 'PUT') {
            $rules['date'] = 'nullable';
            $rules['start_time'] = 'nullable';
            $rules['end_time'] = 'nullable';
        }

        return $rules;
    }
}

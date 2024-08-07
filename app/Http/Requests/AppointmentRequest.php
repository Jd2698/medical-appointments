<?php

namespace App\Http\Requests;

use App\Enums\AppointmentStatusEnum;
use Carbon\Carbon;
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
        //hay una validación en el frontend
        $minDate = Carbon::now()->addDays(1)->toDateString();

        $rules = [
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|after_or_equal:' . $minDate,
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

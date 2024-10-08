<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'specialty_id',
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'comment',
        'status',
    ];

    protected $appends = ['format_end_time', 'format_start_time'];

    public function formatEndTime(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                // 2024-07-27 4:15
                $appointmentDate = $attributes['date'] . ' ' . $attributes['end_time'];

                $carbonDate = Carbon::createFromFormat('Y-d-m H:i:s', $appointmentDate);

                $formattedDate = $carbonDate->format('H:i');
                return $formattedDate;
            },
        );
    }

    public function formatStartTime(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                // 4:15
                $appointmentDate = $attributes['date'] . ' ' . $attributes['start_time'];

                $carbonDate = Carbon::createFromFormat('Y-d-m H:i:s', $appointmentDate);

                $formattedDate = $carbonDate->format('H:i');
                return $formattedDate;
            },
        );
    }

    protected $casts = [
        'start_time' => 'datetime:H:i',
        // 'start_time' => 'datetime:H:i',
        // 'date' => 'datetime:Y-m-d H:i',
    ];

    public static function formatAppointmentTimes(Carbon $carbonDate, int $endTimeInMinutes): array
    {
        $timeFormatted = $carbonDate->format('H:i');

        $carbonDateFinish = clone $carbonDate;
        $carbonDateFinish->addMinutes($endTimeInMinutes);
        $timeFinishFormatted = $carbonDateFinish->format('H:i');

        return [
            'start_time' => $timeFormatted,
            'end_time' => $timeFinishFormatted
        ];
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id', 'id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }
}

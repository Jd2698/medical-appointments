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
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'comment',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
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

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }
}

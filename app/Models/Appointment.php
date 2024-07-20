<?php

namespace App\Models;

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
        'appointment_date',
        'notes',
        'status',
    ];

    protected $appends = ['format_notes'];

    protected $casts = [
        // 'appointment_date' => 'datetime:Y-m-d',
        'appointment_date' => 'datetime:Y-m-d H:i',
    ];

    protected function formatNotes(): Attribute
    {
        return Attribute::make(
            // get: fn (string $value, $attributes) => Str::limit($attributes['notes'], 40,  '...'),
            get: function ($value, $attributes) {
                return Str::limit($attributes['notes'], 50,  '...');
            },
        );
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

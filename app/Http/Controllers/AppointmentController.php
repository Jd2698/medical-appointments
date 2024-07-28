<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatusEnum;
use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AppointmentController extends Controller
{

    // validar rangos de hora - debería ser por especialidad
    public function rangeValidation(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|in:15,30,60'
        ]);

        // 2024-07-27 4:15
        $appointmentDate = $request->date . ' ' . $request->start_time;
        $carbonDate = Carbon::createFromFormat('Y-m-d H:i', $appointmentDate);

        $result = Appointment::formatAppointmentTimes($carbonDate, $request->end_time);

        $exists = Appointment::where('date', $request->date)
            ->where(function ($query) use ($result) {
                $query->where(function ($query) use ($result) {
                    $query->where('start_time', '<', $result['end_time'])
                        ->where('end_time', '>', $result['start_time']);
                });
            })
            ->exists();

        return response()->json(["isValid" => !$exists]);
    }

    public function index()
    {
        $specialties = Specialty::all();
        $doctors = Doctor::with('user')->get();
        $patients = Patient::with('user')->get();

        $appointmentsStatuses = AppointmentStatusEnum::cases();
        $appointments = Appointment::with('patient.user', 'doctor.user')->get();

        return Inertia::render(
            'Appointments/Index',
            compact(
                'appointments',
                'appointmentsStatuses',
                'specialties',
                'patients',
                'doctors'
            )
        );
    }

    public function store(AppointmentRequest $request)
    {
        // 2024-07-27 4:15
        $appointmentDate = $request->date . ' ' . $request->start_time;
        $carbonDate = Carbon::createFromFormat('Y-m-d H:i', $appointmentDate);

        $result = Appointment::formatAppointmentTimes($carbonDate, $request->end_time);

        $request->merge(['start_time' => $result["start_time"], 'end_time' => $result["end_time"]]);

        Appointment::create($request->all());
    }

    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->all());
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
    }
}

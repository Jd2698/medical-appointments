<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatusEnum;
use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AppointmentController extends Controller
{

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
        Appointment::create($request->all());
    }


    public function show(Appointment $appointment)
    {
        //
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

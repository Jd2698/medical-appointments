<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AppointmentController extends Controller
{

    public function index()
    {
        $appointments = Appointment::with(['patient.user', 'doctor.user'])->orderBy('status', 'DESC')->get();
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();

        $appointmentsStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];

        return Inertia::render('Appointments/Index', compact('appointments', 'patients', 'doctors', 'appointmentsStatuses'));
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

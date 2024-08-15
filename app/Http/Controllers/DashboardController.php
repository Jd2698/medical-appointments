<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return Inertia::render('Welcome');
        }

        return Inertia::render('Dashboard');
    }

    public function dashboard()
    {
        // si es un paciente
        if (!Auth::user()->hasAnyRole(['admin', 'doctor'])) {

            $appointments = Appointment::with('doctor.user', 'patient', 'specialty')
                ->where('patient_id', Auth::user()->id)
                ->get()
                ->map(function ($appointment) {
                    return [
                        'specialty_name' => $appointment->specialty->name,
                        'doctor_name' => $appointment->doctor->user->name,
                        'patient_name' => $appointment->patient->name,
                        'status' => $appointment->status,
                        'date' => $appointment->date,
                        'start_time' => $appointment->format_start_time,
                        'end_time' => $appointment->format_end_time,
                    ];
                });
            $isPatient = true;
            return Inertia::render('ClientWelcome', compact('appointments', 'isPatient'));
        }

        $usersQuantity = User::count();
        $adminQuantity = User::role('admin')->count();
        $disabledUsersQuantity = User::where('is_active', '0')->count();

        $doctorsQuantity = Doctor::count();
        $disabledDoctorsQuantity = Doctor::whereHas('user', function ($query) {
            $query->where('is_active', 0);
        })->count();

        $patientsQuantity = Patient::count();
        $disabledPatientsQuantity = Patient::whereHas('user', function ($query) {
            $query->where('is_active', 0);
        })->count();

        $specialtiesQuantity = Specialty::count();
        $disabledSpecialtiesQuantity = Specialty::where('is_active', 0)->count();

        $appointmentsQuantity = Appointment::count();
        $pendingAppointmentsQuantity = Appointment::where('status', 'pending')->count();
        $cancelledAppointmentsQuantity = Appointment::where('status', 'cancelled')->count();
        $confirmedAppointmentsQuantity = Appointment::where('status', 'confirmed')->count();
        $completedAppointmentsQuantity = Appointment::where('status', 'completed')->count();

        $appointmentsStatuses = [
            'pending' => $pendingAppointmentsQuantity,
            'cancelled' => $cancelledAppointmentsQuantity,
            'confirmed' => $confirmedAppointmentsQuantity,
            'completed' => $completedAppointmentsQuantity
        ];

        return Inertia::render('Dashboard', compact(
            'adminQuantity',
            'usersQuantity',
            'disabledUsersQuantity',
            'doctorsQuantity',
            'disabledDoctorsQuantity',
            'patientsQuantity',
            'disabledPatientsQuantity',
            'specialtiesQuantity',
            'disabledSpecialtiesQuantity',
            'appointmentsQuantity',
            'appointmentsStatuses'
        ));
    }

    public function redirectBack()
    {
        return redirect()->back();
    }
}

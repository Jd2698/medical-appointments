<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

use Inertia\Inertia;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function dashboard()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();

        $appointmentsStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        $appointments = Appointment::with('patient.user', 'doctor.user')->get();

        return Inertia::render('Dashboard2', compact('appointments', 'appointmentsStatuses', 'patients', 'doctors'));
    }
}

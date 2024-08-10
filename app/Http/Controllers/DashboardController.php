<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            // 'canLogin' => Route::has('login'),
            // 'canRegister' => Route::has('register'),
            // 'laravelVersion' => Application::VERSION,
            // 'phpVersion' => PHP_VERSION,
        ]);
    }

    public function dashboard()
    {
        return to_route('appointments.index');
    }
}

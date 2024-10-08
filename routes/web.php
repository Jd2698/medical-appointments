<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/pruebas', function () {
//     return Inertia::render('Pruebas');
// });

Route::get('/', [DashboardController::class, 'index']);

// modificar la ruta existente
Route::get('/register', [DashboardController::class, 'index'])->name('register');
// ***************************

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    // 'role:admin'
])->group(function () {
    //modificar las rutas existentes
    Route::get('/user', [DashboardController::class, 'redirectBack']);
    Route::get('/user/profile', [DashboardController::class, 'redirectBack']);
    Route::get('/user/profile-photo', [DashboardController::class, 'redirectBack']);
    Route::get('/user/profile-information', [DashboardController::class, 'redirectBack']);
    // ***************************

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::group(['prefix' => 'admin/users', 'controller' => UserController::class], function () {
        Route::get('/', 'index')->name('users.index')
            ->middleware('can:read users');

        Route::get('/{user}', 'show')->name('users.show');

        Route::post('/', 'store')->name('users.store')
            ->middleware('can:create users');

        Route::put('/{user}', 'update')->name('users.update')
            ->middleware('can:update users');
    });

    Route::group(['prefix' => 'admin/doctors', 'controller' => DoctorController::class], function () {
        Route::get('/get-all-doctors', 'getAll')->name('doctors.get-all-doctors');

        Route::get('/get-specialty-doctors', 'getSpecialtyDoctors')->name('doctors.get-specialty-doctors');

        Route::get('/', 'index')->name('doctors.index');

        Route::post('/', 'store')->name('doctors.store')
            ->middleware('can:create users');

        Route::put('/{doctor}', 'update')->name('doctors.update')
            ->middleware('can:update users');
    });

    Route::group(['prefix' => 'admin/patients', 'controller' => PatientController::class], function () {
        Route::get('/', 'index')->name('patients.index');

        Route::post('/', 'store')->name('patients.store')
            ->middleware('can:create users');

        Route::put('/{patient}', 'update')->name('patients.update')
            ->middleware('can:update users');
    });

    Route::group(['prefix' => 'admin/appointments', 'controller' => AppointmentController::class], function () {
        Route::get('/range-validation', 'rangeValidation')->name('appointments.rangeValidation');

        Route::get('/', 'index')->name('appointments.index');
        Route::post('/', 'store')->name('appointments.store');
        Route::put('/{appointment}', 'update')->name('appointments.update');
        Route::delete('/{appointment}', 'destroy')->name('appointments.destroy');
    });

    Route::group(['prefix' => 'admin/specialties', 'controller' => SpecialtyController::class], function () {
        Route::get('/', 'index')->name('specialties.index');

        Route::post('/', 'store')->name('specialties.store')
            ->middleware('can:create specialties');

        Route::put('/{specialty}', 'update')->name('specialties.update')
            ->middleware('can:update specialties');
    });
});

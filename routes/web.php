<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::group(['prefix' => 'dashboard/users', 'controller' => UserController::class], function () {
        Route::get('/', 'index')->name('users.index')->middleware('can:read users');
        Route::post('/', 'store')->name('users.store')->middleware('can:create users');
        Route::get('/{user}', 'show')->name('users.show');
        Route::put('/{user}', 'update')->name('users.update')->middleware('can:update users');
        Route::delete('/{user}', 'destroy')->name('users.destroy')->middleware('can:delete users');
    });

    Route::group(['prefix' => 'dashboard/appointments', 'controller' => AppointmentController::class], function () {
        Route::get('/', 'index')->name('appointments.index');
        Route::post('/', 'store')->name('appointments.store');
        Route::put('/{appointment}', 'update')->name('appointments.update');
        Route::delete('/{appointment}', 'destroy')->name('appointments.destroy');
    });

    Route::group(['prefix' => 'dashboard/doctors', 'controller' => DoctorController::class], function () {
        Route::get('/get-all-doctors', 'getAll')->name('doctors.get-all-doctors');
        Route::get('/', 'index')->name('doctors.index');
        Route::post('/', 'store')->name('doctors.store');
        Route::put('/{doctor}', 'update')->name('doctors.update');
        Route::delete('/{doctor}', 'destroy')->name('doctors.destroy');
    });
});

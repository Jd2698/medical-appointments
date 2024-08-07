<?php

namespace App\Http\Controllers;

use App\Enums\GendersEnum;
use App\Http\Requests\PatientRequest;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user.roles')
            ->whereHas('user', function ($query) {
                $query->where('is_active', 1);
            })->get();
        // ->whereHas('user', function ($query) {
        //     $query->whereNotNull('id');

        $genders = GendersEnum::cases();

        return Inertia::render(
            'Patients/Index',
            compact(
                'patients',
                'genders'
            )
        );
    }

    public function store(PatientRequest $request)
    {
        $request->merge(['password' => bcrypt($request->password)]);
        $user = User::create($request->all())->assignRole('patient');

        Patient::create([
            'user_id' => $user->id,
            'eps' => $request->eps,
            'medical_history' => $request->has('medical_history')
                ? $request->medical_history
                : null
        ]);
    }

    public function update(PatientRequest $request, Patient $patient)
    {
        $user = User::findOrFail($patient->user_id);

        if ($request->password) {
            $request->merge(['password' => bcrypt($request->password)]);
        } else {
            $request = $request->except(['password']);
        }

        $user->update($request);

        $patient->update([
            'eps' => $request['eps'],
            'medical_history' => isset($request['medical_history'])
                ? $request['medical_history']
                : $patient->medical_history
        ]);
    }

    public function destroy(Patient $patient)
    {
        //
    }
}

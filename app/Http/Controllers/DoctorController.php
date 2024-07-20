<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorRequest;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class DoctorController extends Controller
{

    public function getAll()
    {
        $doctor = Doctor::with('user.roles')
            ->whereHas('user', function ($query) {
                $query->whereNotNull('id');
            })->get();

        return DataTables::of($doctor)
            ->addColumn('action', function ($row) {
                return
                    "<div class='flex gap-2 justify-center' data-role='actions'><button onclick='event.preventDefault();' data-id='{$row->id}' role='edit' class='bg-[#2d6a4f] px-2 py-1 rounded'>Edit</button><button onclick='event.preventDefault();' data-id='{$row->id}' role='delete' class='bg-[#1b4332] px-2 py-1 rounded'>Delete</button></div>";
            })
            ->rawColumns(['action'])
            ->make();
    }

    public function index()
    {
        $doctors = Doctor::with('user.roles')
            ->whereHas('user', function ($query) {
                $query->whereNotNull('id');
            })->get();

        $genders = ['male', 'female'];
        return Inertia::render('Doctors/Index', compact('doctors', 'genders'));
    }

    public function store(DoctorRequest $request)
    {
        $data = $request->except('specialty');
        $data['password'] = bcrypt($request->password);

        $user = User::create($data)->assignRole('doctor');
        Doctor::create([
            'user_id' => $user->id,
            'specialty' => $request->specialty
        ]);
    }

    public function update(Request $request, Doctor $doctor)
    {
        // dd($request->all());
        $user = User::find($doctor->user_id);

        if ($request->password) {
            $password = bcrypt($request->password);
            $request->merge(['password' => $password]);
        } else {
            $request->request->remove('password');
        }

        $user->update($request->all());
        $user->syncRoles($request->role);

        $doctor->update($request->only('specialty'));
    }

    public function destroy(Doctor $doctor)
    {
        //
    }
}

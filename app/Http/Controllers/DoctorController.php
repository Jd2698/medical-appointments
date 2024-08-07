<?php

namespace App\Http\Controllers;

use App\Enums\GendersEnum;
use App\Http\Requests\DoctorRequest;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class DoctorController extends Controller
{

    // No se usa, ajax de datatable
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

    // filtro para el formulario
    public function getSpecialtydoctors(Request $request)
    {
        $request->validate([
            'specialty_id' => 'exists:specialties,id',
        ]);

        $doctors = Doctor::with('user')
            ->where('specialty_id', $request->query('specialty_id'))
            ->get()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'user_id' => $doctor->user_id,
                    'specialty_id' => $doctor->specialty_id,
                    'ratings' => $doctor->ratings,
                    'user_name' => $doctor->user ? $doctor->user->name : null,
                ];
            });

        return response()->json(["doctors" => $doctors]);
    }

    public function index()
    {
        $doctors = Doctor::with('user.roles', 'specialty')
            ->whereHas('user', function ($query) {
                $query->where('is_active', 1);
            })->get();

        $genders = GendersEnum::cases();
        $specialties = Specialty::all();

        return Inertia::render(
            'Doctors/Index',
            compact(
                'doctors',
                'genders',
                'specialties'
            )
        );
    }

    public function store(DoctorRequest $request)
    {
        $request->merge(['password' => bcrypt($request->password)]);
        $user = User::create($request->all())->assignRole(['doctor', 'patient']);

        Doctor::create([
            'user_id' => $user->id,
            'specialty_id' => $request->specialty_id
        ]);
    }

    public function update(DoctorRequest $request, Doctor $doctor)
    {
        $user = User::findOrFail($doctor->user_id);

        if ($request->password) {
            $request->merge(['password' => bcrypt($request->password)]);
        } else {
            $request = $request->except(['password']);
        }
        $user->update($request);

        $doctor->update([
            'specialty_id' => $request['specialty_id']
        ]);
    }

    public function destroy(Doctor $doctor)
    {
        //
    }
}

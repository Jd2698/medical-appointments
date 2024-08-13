<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecialtyRequest;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SpecialtyController extends Controller
{
    public function index()
    {
        // $specialties = Specialty::with('doctors')->get();
        $specialties = Specialty::withCount('doctors')->get();
        return Inertia::render(
            'Specialties/Index',
            compact('specialties')
        );
    }

    public function store(SpecialtyRequest $request)
    {
        Specialty::create($request->all());
    }

    public function update(SpecialtyRequest $request, Specialty $specialty)
    {
        $specialty->update($request->all());
    }
}

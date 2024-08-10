<?php

namespace App\Http\Controllers;

use App\Enums\GendersEnum;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderBy('is_active', 'desc')->get();
        $genders = GendersEnum::cases();

        return Inertia::render(
            'Users/Index',
            compact(
                'users',
                'genders'
            )
        );
    }

    public function store(UserRequest $request)
    {
        $data = $request->except('role', 'password_confirmation');
        $data['password'] = bcrypt($request->password);

        User::create($data)->assignRole('admin');
    }

    public function show(User $user)
    {
        return response()->json(['user' => $user]);
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->except('password', 'password_confirmation', 'role');

        if ($request->password) {
            $password = bcrypt($request->password);
            $request->merge(['password' => $password]);

            $user->update($request->all());
        } else {
            $user->update($data);
        }
        $user->syncRoles($request->role);
    }

    public function destroy(User $user)
    {
        // $user->delete();
    }
}

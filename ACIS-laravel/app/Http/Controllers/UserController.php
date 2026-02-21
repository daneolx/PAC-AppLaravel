<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('name')
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"))
            ->paginate(20);

        return view('users.user_list', compact('users'));
    }

    public function create()
    {
        return view('users.user_form', ['user' => new User(), 'roles' => User::ROLES]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:8|confirmed',
            'role'       => ['required', Rule::in(array_keys(User::ROLES))],
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('user_list')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        return view('users.user_form', ['user' => $user, 'roles' => User::ROLES]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'email'      => ['required', 'email', Rule::unique('users')->ignore($user)],
            'password'   => 'nullable|min:8|confirmed',
            'role'       => ['required', Rule::in(array_keys(User::ROLES))],
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('user_list')->with('success', 'Usuario actualizado correctamente.');
    }

    public function profile(Request $request)
    {
        return view('users.profile_form', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        $user->update($data);

        return redirect()->route('profile')->with('success', 'Perfil actualizado.');
    }

    public function auditLog(Request $request)
    {
        $logs = AuditLog::with('user')->latest('created_at')->paginate(50);
        return view('users.audit_list', compact('logs'));
    }
}

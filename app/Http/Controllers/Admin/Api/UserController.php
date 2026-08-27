<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $query = User::query()->with('roles');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate($perPage);

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|in:Admin,Pegawai',
        ]);

        // Only SuperAdmin or Admin can create users, but Admins can only
        // assign Pegawai role (defense-in-depth against privilege escalation).
        $user = $request->user();
        $allowedRoles = $user->hasRole('SuperAdmin')
            ? ['Admin', 'Pegawai']
            : ['Pegawai'];

        if (!in_array($request->role, $allowedRoles, true)) {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk menunjuk role tersebut.',
            ], 403);
        }

        $email = $request->email;
        // Generate name from email (before the @ symbol)
        $name = Str::before($email, '@');

        // Generate a secure temporary password and send a reset link
        $tempPassword = Str::random(24);

        $user = User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($tempPassword),
        ]);

        $user->assignRole($request->role);

        // Send password reset email so user can set their own password
        Password::sendResetLink(['email' => $email]);

        return response()->json([
            'message' => 'User created successfully. A password reset link has been sent to the user.',
            'data'    => $user->load('roles'),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string|in:Admin,Pegawai',
        ]);

        $user->email = $request->email;
        $user->save();

        $user->syncRoles([$request->role]);

        return response()->json(['message' => 'User updated successfully', 'data' => $user->load('roles')]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login user and create access token.
     */
    public function login(
        Request $request
    ): JsonResponse {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        $user = User::query()
            ->where(
                'email',
                $validated['email']
            )
            ->first();

        if (
            !$user ||
            !Hash::check(
                $validated['password'],
                $user->password
            )
        ) {
            throw ValidationException::withMessages([
                'email' => [
                    'Email atau password tidak sesuai.',
                ],
            ]);
        }

        /*
         * Untuk sementara kita hapus token login
         * sebelumnya agar testing lebih sederhana.
         *
         * Artinya satu user hanya memiliki token
         * login terbaru.
         */
        $user->tokens()->delete();

        $token = $user
            ->createToken(
                'kms-auth-token'
            )
            ->plainTextToken;

        // Sync with web session
        Auth::login($user);

        return response()->json([
            'message' =>
                'Login berhasil.',

            'data' => [
                'user' => [
                    'id' =>
                        $user->id,

                    'name' =>
                        $user->name,

                    'email' =>
                        $user->email,
                        
                    'roles' =>
                        $user->getRoleNames(),
                ],

                'token' =>
                    $token,

                'token_type' =>
                    'Bearer',
            ],
        ]);
    }

    /**
     * Get authenticated user.
     */
    public function me(
        Request $request
    ): JsonResponse {
        $user =
            $request->user();

        return response()->json([
            'message' =>
                'Data pengguna berhasil diambil.',

            'data' => [
                'user' => [
                    'id' =>
                        $user->id,

                    'name' =>
                        $user->name,

                    'email' =>
                        $user->email,
                        
                    'roles' =>
                        $user->getRoleNames(),
                ],
            ],
        ]);
    }

    /**
     * Logout current access token.
     */
    public function logout(
        Request $request
    ): JsonResponse {
        $token =
            $request
                ->user()
                ->currentAccessToken();

        if ($token) {
            $token->delete();
        }
        
        // Clear web session
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' =>
                'Logout berhasil.',
        ]);
    }
}
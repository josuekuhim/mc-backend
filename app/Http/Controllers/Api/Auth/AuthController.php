<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clinician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $clinician = Clinician::where('email', $request->email)->first();

        if (!$clinician || !Hash::check($request->password, $clinician->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        // Criar token para API
        $token = $clinician->createToken('auth_token')->plainTextToken;

        return response()->json([
            'clinician' => [
                'id' => $clinician->id,
                'first_name' => $clinician->first_name,
                'last_name' => $clinician->last_name,
                'email' => $clinician->email,
                'full_name' => $clinician->full_name,
            ],
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function me(Request $request)
    {
        $clinician = $request->user();
        
        return response()->json([
            'clinician' => [
                'id' => $clinician->id,
                'first_name' => $clinician->first_name,
                'last_name' => $clinician->last_name,
                'email' => $clinician->email,
                'full_name' => $clinician->full_name,
            ]
        ]);
    }
}
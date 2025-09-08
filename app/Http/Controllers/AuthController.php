<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function login(Request $request) {
        $errors = Utils::validateRequest($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (count($errors) > 0) {
            return response()->json(['error' => true, 'errors' => $errors], 422);
        }

        $user = User::where('email', $request->email)->whereNull('deleted_at')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => true, 'errors' => ['E-mail e senha fornecidos estão incorretas.']], 401);
        }

        $token = $user->createToken('user', ['api:user']);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response(null, 200);
    }
}

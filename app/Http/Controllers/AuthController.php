<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
/**
 * @api {post} /login Bejelentkezés
 * @apiVersion 1.0.0
 * @apiName Login
 * @apiGroup Auth
 *
 * @apiBody {String} email Felhasználó e-mail címe
 * @apiBody {String} password Jelszó
 *
 * @apiSuccess {Object} user A bejelentkezett felhasználó
 * @apiSuccess {String} token Bearer token a védett végpontokhoz
 *
 * @apiError (401) {String} message Hibás e-mail vagy jelszó
 */
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid email or password',
        ], 401);
    }

    $user->tokens()->delete();

    $token = $user->createToken('access')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
    ]);
}
}
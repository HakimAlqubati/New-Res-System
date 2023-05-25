<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = $request->user();

            $token = $user->createToken('MyApp')->accessToken; 
            return response()->json([
                'token' => $token,
                'user_id' => $user->id,
                'user_name'=>$user->name,
                'email'=>$user->email,
                'owner_id'=>$user->owner_id,
                'role_id' => $user->roles[0]->id, 
            ]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function getCurrnetUser(Request $request)
    {
        return $request->user();
    }
}

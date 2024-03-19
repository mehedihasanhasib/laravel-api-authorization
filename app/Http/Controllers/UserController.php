<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function register(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'digits:4'
        ]);
        $user = User::create($validated_data);
        $token = $user->createToken('auth_token')->accessToken;
        return response()->json([
            'token' => $token,
            'msg' => 'User Registered Successfully',
            'status' => 1
        ], 200);
    }

    public function login(Request $request)
    {
        $validated_data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $validated_data['email'];
        $password = $validated_data['password'];

        try {
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $user = Auth::user();
                $token = $user->createToken('auth_token')->accessToken;
                return response()->json([
                    'token' => $token,
                    'msg' => 'User logged in Successfully',
                    'status' => 1
                ], 200);
            } else {
                return response()->json([
                    'msg' => 'Invalid Email or Password',
                    'status' => 0
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function get($id)
    {
        $user = User::where('id', $id)->first();
        return response()->json($user);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $loginData = $request->only(['email','password']);
        if(!$token = auth()->attempt($loginData)){
            return response()->json(['message' => 'Invalid Credentials'], 400);
        }

        $user = User::where('email', auth()->user()->email)->first();
        $user->last_login = now('UTC');
        $user->save();
        return response()->json(['token' => $token, 'user' => auth()->user()]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'bail|required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required',
            'role' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(),400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $user->save();
        return response()->json(['message' => 'Sign up successful']);
    }

    public function getusers(Request $request){
        $users = User::with('orders')->get();
        return response()->json(['data' => $users]);
    }
}

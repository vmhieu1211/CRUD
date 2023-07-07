<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password, [])) {
            return response()->json(
                [
                    'message' => 'User not exist'
                ],
                404
            );
        }
        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json(
            [
                'access_token' =>  $token,
                'type_token' => 'Bearer'
            ],
            404
        );
    }

    public function register(Request $request)
    {
        $messages = [
            'email.email' => "Error email",
            'email.required' => "Email required",
            'password.required' => "Password required"
        ];
        $validate = Validator::make($request->all(), [
            'email' => 'email|required',
            'password' => 'required'
        ], $messages);
        if ($validate->fails()) {
            return response()->json(
                [
                    'message' => $validate->errors()
                ],
                404
            );
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json(
            [
                'message' => 'created'
            ],
            200
        );
    }

    public function user(Request $request)
    {
        return  $request->user();
    }


    public function logout(Request $request)
    {
        //revoke all tokens
        // auth()->user()->tokens()->delete();

        //revoke the tokens that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        //revoke a specific token
        // auth()->user()->tokens()->where('id',$tokenId) ->delete();

        return response()->json(
            [
                'message' => 'Logout',
                'data' =>  $request->user()->currentAccessToken()
            ],
            200
        );
    }
}

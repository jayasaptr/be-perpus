<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
       //set validasi
       $validator = Validator::make($request->all(), [
        'email'    => 'required|email',
        'password' => 'required',
    ]);
    
    //response error validasi
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    //get "email" dan "password" dari input
    $credentials = $request->only('email', 'password');

    //check jika "email" dan "password" tidak sesuai
    if(!$token = auth()->guard('api')->attempt($credentials)) {

        //response login "failed"
        return response()->json([
            'success' => false,
            'message' => 'Email or Password is incorrect'
        ], 401);

    }
    
    //response login "success" dengan generate "Token"
    return response()->json([
        'success' => true,
        'user'    => auth()->guard('api')->user(),  
        'token'   => $token   
    ], 200);
    }
}

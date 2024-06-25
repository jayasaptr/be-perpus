<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        //set validasi
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'email'         => 'required|email|unique:users',
            'password'      => 'required',
            'nisn'          => 'required|unique:users',
            'class'         => 'required',
            'date_of_birth' => 'required',
            'address'       => 'required',
            'phone_number'  => 'required',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //response error validasi
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image_name = time().'.'.$image->extension();
        $image->storeAs('public/images', $image_name);

        //create data user
        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'nisn'          => $request->nisn,
            'class'         => $request->class,
            'date_of_birth' => $request->date_of_birth,
            'address'       => $request->address,
            'phone_number'  => $request->phone_number,
            'image'         => $image_name,
            'role'          => $request->role ?? 'user',
        ]);

        //response data user
        return response()->json(new UserResource(true, 'Register Success', $user), 200);
    }

    
}

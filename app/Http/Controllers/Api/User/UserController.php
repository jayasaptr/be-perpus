<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //get data all user with pagination and search by name
    public function getUser(Request $request)
    {
        //set pagination
        $pagination = $request->pagination ?? 5;

        //set search by name
        $search = $request->search ?? '';

        //get data user
        $users = User::where('name', 'like', '%'.$search.'%')->paginate($pagination);

        //response data user
        return response()->json(new UserResource(true, 'Data User', $users), 200);
    }

    //get data user by id
    public function getUserById($id)
    {
        //get data user by id
        $user = User::find($id);

        //response data user
        return response()->json(new UserResource(true, 'Data User', $user), 200);
    }

    //create data user
    public function createUser(Request $request)
    {
        //validate data user
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'nisn' => 'required',
            'class' => 'required',
            'date_of_birth' => 'required',
            'address' => 'required',
            'phone_number' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        //upload image user
        $image = $request->file('image');
        $image_name = time().'.'.$image->getClientOriginalExtension();
        $image->storeAs('public/images', $image_name);

        //create data user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->nisn = $request->nisn;
        $user->class = $request->class;
        $user->date_of_birth = $request->date_of_birth;
        $user->address = $request->address;
        $user->phone_number = $request->phone_number;
        $user->image = $image_name;
        $user->role = 'user';
        $user->save();

        //response data user
        return response()->json(new UserResource(true, 'Data User', $user), 200);

    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            if($user->image != 'default.jpg') {
                Storage::delete('public/images/'.$user->image);
            }

            $image = $request->file('image');
            $image_name = time().'.'.$image->getClientOriginalExtension();
            $image->storeAs('public/images', $image_name);
            $data['image'] = $image_name;
        }

        $user->update($data);

        return response()->json(new UserResource(true, 'Data User', $user), 200);
    }

    //update image user by id
    public function updateImageUser(Request $request, $id)
    {
        //validate image user
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        //get data user by id
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        //delete image user
        if($user->image != 'default.jpg') {
            //foldernya terletak di storage/app/public/images   
            Storage::delete('public/images/'.$user->image);
        }

        //upload image user
        $image = $request->file('image');
        $image_name = time().'.'.$image->getClientOriginalExtension();
        $image->storeAs('public/images', $image_name);

        //update image user
        $user->image = $image_name;
        $user->save();

        //response data user
        return response()->json(new UserResource(true, 'Data User', $user), 200);
    }
    

    //delete data user by id dan jangan lupa hapus image user
    public function deleteUser($id)
    {
        //get data user by id
        $user = User::find($id);

        //delete image user
        if($user->image != 'default.jpg') {
            //foldernya terletak di storage/app/public/images   
            Storage::delete('public/images/'.$user->image);
        }

        //delete data user by id
        $user->delete();

        //response data user
        return response()->json(new UserResource(true, 'Data User', $user), 200);
    }
}

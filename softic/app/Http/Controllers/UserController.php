<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view("welcome");
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // multiple data insert
        foreach($data['users'] as $user)
        {
            $add = new User();
            $add->name = $user['name'];
            $add->email = $user['email'];
            $add->password=$user['password'];
            $add->date_of_birth = $user['date_of_birth'];
            $add->save();

        }
        return response()->json(['message'=>'successfully added'],201);
    }

    public function update(Request $request, $id)
    {

        $update =  User::find($id);
        if(!$update){
            return response()->json(['error'=>'User not found'],404);
        }
        $update->name = $request->name;
        $update->password = $request->password;
        $update->date_of_birth = $request->date_of_birth;
        $update->save();
        return response()->json(['message'=> 'Update data successfully'],202);
    }


    public function show()
    {
        $crud = User::latest()->get();
        return response()->json($crud);
    }
    public function edit($id)
    {
        $edit = User::findOrFail($id);
        return response()->json(['edit'=> $edit]);

    }
//
    public function destroy($id)
    {
        $remove = User::findOrFail($id);
        $remove->delete();
        return response()->json(['message'=> 'Delete successfully'],200);
    }
}

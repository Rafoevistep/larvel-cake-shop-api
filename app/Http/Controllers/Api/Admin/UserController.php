<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function index()
    {
        // All Users showing
       $user = User::all();

       // Return Json Response
       return response()->json([
            count($user),
            'user' => $user
       ],200,);

    }



    public function update_profile(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'first_name'=>'required|min:2|max:100',
            'last_name'=>'required|min:2|max:100',
            'phone'=>'required|min:3|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message'=>'Validations fails',
                'errors'=>$validator->errors()
            ],422);
        }

        $user=User::find($id);

        $user->update([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'phone'=>$request->phone,
        ]);

        return response()->json([
            'message'=>'User Profile successfully updated',
        ],200);

        $user->save();
    }


    public function store(Request $request)
    {
        //
    }



    public function show($id)
    {
         // User Detail
         $user =  User::find($id);
         if(!$user){
             return response()->json([
                 'message'=>'User Not Found.'
             ], 404);
         }

         // Return Json Response
         return response()->json([
             'user' => $user
         ],200);
    }


    // public function update(Request $request, $id)
    // {
    //     //
    // }


    public function destroy($id)
    {
        //
    }
}

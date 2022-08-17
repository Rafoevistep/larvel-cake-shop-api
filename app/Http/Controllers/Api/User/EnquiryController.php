<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Wiew All Enquary

        $enquiry = Enquiry::all();

        return response()->json($enquiry);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //User can Send Enquary
        $validator = Validator::make($request->all(), [
            'name' => 'required|name|min:3',
            'email' => 'required|email',
            'message' => 'required|message|min:10|max:255',
        ]);

        $userId = auth('sanctum')->user()->id;

        $enquiry = Enquiry::create([
            'user_id' => $userId,
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message
        ]);

        return response()->json(['message' => 'Your Enquiry Succesfuly Send',$enquiry]);

        $validator->validated();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

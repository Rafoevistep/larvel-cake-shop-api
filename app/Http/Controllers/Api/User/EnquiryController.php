<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnquiryController extends Controller
{
    public function index()
    {
        //Wiew All Enquary

        $enquiry = Enquiry::all();

        return response()->json($enquiry);
    }

    public function store(Request $request)
    {
        //User can Send Enquary
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'email' => 'required|email',
            'message' => 'required|string|min:10|max:255',
        ]);


        $validator->validated();

        $userId = auth('sanctum')->user()->id;

        $enquiry = Enquiry::create([
            'user_id' => $userId,
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message
        ]);

        return response()->json(['message' => 'Your Enquiry Successfully Send', $enquiry]);

    }

}

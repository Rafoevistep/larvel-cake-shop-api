<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactPageController extends Controller
{
    public function index(): JsonResponse
    {
        //Show Contact  Information
        $about = ContactPage::latest()->first();
        return response()->json($about, 200);
    }


    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|min:5',
            'phone' => 'required|string|min:5',
            'email' => 'required|email',
        ]);

        $validator->validated();

        // Create Product
        $contact = ContactPage::updateOrCreate([
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);


            // Return Json Response
            return response()->json($contact, 200);


    }

}

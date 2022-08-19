<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactPageController extends Controller
{
    public function index()
    {
        //Show Contact  Information
        $about = ContactPage::latest()->first();
        return response()->json($about, 200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|min:5',
            'phone' => 'required|string|min:5',
            'email' => 'required|email',
        ]);

        // Create Product
        $contact = ContactPage::updateOrCreate([
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        if ($contact == null) {
            // Return Json Response
            return response()->json($contact, 200);
        } else {
            return response()->json(['message' => 'You have already added Information in Contact Page']);
        }
        $validator->validated();
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Show Contact  Information
       $about = ContactPage::latest()->first();
       return response()->json($about, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

            if($contact == null){
                 // Return Json Response
                return response()->json($contact, 200);
            }else{
                return response()->json(['message' => 'You have already added Information in Contact Page']);
            }
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

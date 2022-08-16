<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AboutUsPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Show About us Information
       $about = AboutPage::latest()->first();
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
        'name' => 'required|string|min:5',
        'description' => 'required|string|min:10',
        ]);

        // Create Product
        $about = AboutPage::updateOrCreate([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if($about == null){
             // Return Json Response
            return response()->json($about, 200);
        }else{
            return response()->json(['message' => 'You have already added Information in About Page']);
        }
        $validator->validated();
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

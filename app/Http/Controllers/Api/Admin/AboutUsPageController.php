<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AboutUsPageController extends Controller
{

    public function index()
    {
        //Show About us Information
        $about = AboutPage::latest()->first();
        return response()->json($about, 200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:5',
            'description' => 'required|string|min:10',
        ]);

        $validator->validated();

        // Create Product
        $about = AboutPage::updateOrCreate([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($about == null) {
            // Return Json Response
            return response()->json($about, 200);
        } else {
            return response()->json(['message' => 'You have already added Information in About Page']);
        }

    }

}

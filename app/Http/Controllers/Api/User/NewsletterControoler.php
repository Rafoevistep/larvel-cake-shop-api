<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Newsletter_subscribers;
use Darryldecode\Cart\Validators\Validator;
use Illuminate\Http\Request;

class NewsletterControoler extends Controller
{

    public function index()
    {
        //Show Shubscropes Emails

        $subscripe = Newsletter_subscribers::all();
        return response()->json($subscripe);
    }


    public function store(Request $request, Newsletter_subscribers $subscripe )
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        // $subscripe = Newsletter_subscribers::where(['email' => $subscripe->email])->first();

        if ($validator->fails()) {
            return response()->json(['message' => 'This Mail Alredy Subscriped',$subscripe]);
        }
        else
        {
            $subscripe = Newsletter_subscribers::updateOrCreate([
                    'email' => $request->email,
                    ]);
            return response()->json(['message' => 'Your Mail Subscriped',$subscripe]);
        }
        $validator->validated();
    }



}

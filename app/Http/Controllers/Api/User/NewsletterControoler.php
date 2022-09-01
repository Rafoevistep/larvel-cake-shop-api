<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscribers;
use Darryldecode\Cart\Validators\Validator;
use Illuminate\Http\Request;

class NewsletterControoler extends Controller
{

    public function index()
    {
        //Show Shubscropes Emails

        $subscripe = NewsletterSubscribers::all();
        return response()->json($subscripe);
    }

    public function store(Request $request, NewsletterSubscribers $subscripe)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        $validator->validated();


        if ($validator->fails()) {
            return response()->json(['message' => 'This Mail Alredy Subscriped', $subscripe]);
        } else {
            $subscripe = NewsletterSubscribers::updateOrCreate([
                'email' => $request->email,
            ]);
            return response()->json([
                'message' => 'Your Mail Subscriped',
                'info' => $subscripe
            ]);
        }

    }
}

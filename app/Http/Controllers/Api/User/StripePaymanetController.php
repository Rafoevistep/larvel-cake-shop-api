<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Stripe;



class StripePaymanetController extends Controller
{
    public function stripePost(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product Not Found.'
            ], 404);
        }

        try {
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET')
            );

            $res = $stripe->tokens->create([
                'card' => [
                    'number' => $request->number,
                    'exp_month' => $request->exp_month,
                    'exp_year' => $request->exp_year,
                    'cvc' => $request->cvc,
                ],
            ]);

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $response = $stripe->charges->create([
                'amount' => $product->price * 100,
                'currency' => 'usd',
                'source' => $res->id,
                'description' => $request->description,
            ]);

            return response()->json([$response->status], 201);
        } catch (Exception $ex) {
            return response()->json([['responce => Eror']], 500);
        }
    }
}

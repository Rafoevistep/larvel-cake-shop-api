<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class StripePaymanetController extends Controller
{
    public function stripeBuyNow(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required|min:15|max:16',
            'cvc' => 'required|min:3|max:4',
            'exp_month' => 'required|integer|min:1|max:12',
            'exp_year' => 'integer|min:' . date("Y") . '|max:' . (date("Y")+5)
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validations fails',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product Not Found.'
            ], 404);
        }

        try {
            $stripe = new \Stripe\StripeClient(
                config('services.stripe.secret')
            );

            $res = $stripe->tokens->create([
                'card' => [
                    'number' => $request->number,
                    'exp_month' => $request->exp_month,
                    'exp_year' => $request->exp_year,
                    'cvc' => $request->cvc,
                ],
            ]);

            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $response = $stripe->charges->create([
                'amount' => $product->price * 100,
                'currency' => 'usd',
                'source' => $res->id,
                'description' => $request->description,
            ]);

            return response()->json($response->status, 201);
        } catch (Exception $e) {
            return response()->json([['response' => 'Error']], 500);
        }
    }

    public function stripeChekoutCard(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required|min:15|max:16',
            'cvc' => 'required|min:3|max:4',
            'exp_month' => 'required|integer|min:1|max:12',
            'exp_year' => 'integer|min:' . date("Y") . '|max:' . (date("Y")+5)
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validations fails',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth('sanctum')->user();

        $cart_items = $user->cartItems;

        $total = $cart_items->map(function ($product) {
            return $product->price * $product->qty;
        })->sum();

        try {
            $stripe = new \Stripe\StripeClient(
                config('services.stripe.secret')
            );

            $res = $stripe->tokens->create([
                'card' => [
                    'number' => $request->number,
                    'exp_month' => $request->exp_month,
                    'exp_year' => $request->exp_year,
                    'cvc' => $request->cvc,
                ],
            ]);

            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $response = $stripe->charges->create([
                'amount' => $total * 100,
                'currency' => 'usd',
                'source' => $res->id,
                'description' => $request->description,
            ]);

            $cart_items->each->delete($cart_items);

            return response()->json($response->status, 201,);

        } catch (Exception $e) {
            return response()->json([['response => Error']], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Stripe;
use Illuminate\Support\Str;


class StripePaymanetController extends Controller
{
    public function stripeBuyNow(Request $request, $id): \Illuminate\Http\JsonResponse
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

    public function stripeChekoutCard(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = auth('sanctum')->user();

        $cart_items = $user->cartItems;

        $total = $cart_items->map(function ($product) {
            return $product->price * $product->qty;
        })->sum();

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
                'amount' => $total * 100,
                'currency' => 'usd',
                'source' => $res->id,
                'description' => $request->description,
            ]);

            return response()->json([$response->status], 201);
        } catch (Exception $e) {
            return response()->json([['response => Error']], 500);
        }
    }
}

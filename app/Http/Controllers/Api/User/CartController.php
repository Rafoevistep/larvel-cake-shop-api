<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{

    public function index()
    {
        $userId = auth('sanctum')->user()->id;

        $cart = Cart::where(['user_id' => $userId])->get();

        $total = $cart->map(function ($product) {
            return $product->price * $product->qty;
        })->sum();


        if ($cart) {
            return response()->json([
                'message' => 'Your Cart',
                'total' => $total,
                'cart' => $cart
            ]);
        } else {
            return response()->json(['message' => 'Your Cart Is Empty']);
        }
    }


    public function store(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'qty' => 'required|integer|',
        ]);

        $validator->validated();

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validations fails',
                'errors' => $validator->errors()
            ], 422);
        }

        $userId = auth('sanctum')->user()->id;

        $AddCart = Cart::where(['user_id' => $userId, 'product_id' => $product->id])->first();

        if ($AddCart) {
            return response()->json(['message' => 'You already Add this product']);;
        }

        $cart = Cart::create([
            'user_id' => $userId,
            'product_id' => $product->id,
            'name' => $product->name,
            'image' => $product->image,
            'price' => $product->price,
            'description' => $product->description,
            'qty' => $request->qty,
        ]);


        return response()->json($cart);
    }


    public function destroy($id)
    {
        //Deleting From cart
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json([
                'message' => 'Product Not Found.'
            ], 404);
        }

        // Delete Product
        $cart->delete();

        return response()->json(['message' => 'Item Deleted']);
    }
}

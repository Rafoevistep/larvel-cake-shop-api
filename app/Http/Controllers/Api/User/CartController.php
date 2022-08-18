<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index()
    {
        $userId = auth('sanctum')->user()->id;

        $cart = Cart::where(['user_id' => $userId])->get();

        if($cart) {
            return response()->json([
                'message' => 'Your Cart',
                'cart' => $cart
            ]);
        }else{
            return response()->json(['message' => 'Your Cart Is Empty']);
        }
    }


    public function store(Request $request, Product $product)
    {
        $userId = auth('sanctum')->user()->id;

        $AddCart = Cart::where(['user_id' => $userId, 'product_id' => $product->id])->first();

        if($AddCart) {
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


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id, Product $product)
    {


    }


    public function destroy($id)
    {
        //Deleting From cart
          $cart = Cart::find($id);
          if(!$cart){
            return response()->json([
               'message'=>'Product Not Found.'
            ],404);
          }

          // Delete Product
          $cart->delete();

        $cart->delete();
        return response()->json(['message' => 'Item Deleted']);
    }



}

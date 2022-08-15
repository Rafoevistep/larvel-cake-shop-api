<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        ]);


        return response()->json($cart);
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
    public function update(Request $request, $id, Product $product)
    {
        if (auth()->user()->id !== $product->user_id) {
            return response()->json(['message' => 'Action Forbidden']);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Cart $cart)
    {
        //deleting From card

        if (auth('sanctum')->user()->id !== $cart->user_id) {
            return response()->json(['message' => 'Action Forbidden']);
        }
        
        $cart->delete();
        return response()->json(['message' => 'Item Deleted', 'Item' => $cart]);
    }
}

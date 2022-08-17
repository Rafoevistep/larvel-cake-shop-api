<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvailableProduct;
use App\Models\Product;
use Darryldecode\Cart\Validators\Validator;
use Illuminate\Http\Request;

class AvailableProductController extends Controller
{

    public function index()
    {
        //Show Avilable Products
        $available = AvailableProduct::where('qty', '>=', 1 )->get();

        return response()->json([
            'message'=>'Available in the bakery',
            $available,
        ]);
    }


    public function store(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'qty' => 'required|integer',
        ]);

        $available = AvailableProduct::create([
            'product_id' => $product->id,
            'qty'=> $request->qty,
        ]);

        return response()->json($available, 200);

        $validator->validated();

    }


    public function show($id)
    {
        //Show Single Avilable Product

        $available = AvailableProduct::find($id);
        if(!$available){
            return response()->json([
                'message'=>'Product Not Avilble.'
            ], 404);
        }

        // Return Json Response
        return response()->json([
            'product_available' => $available
        ],200);
    }

}

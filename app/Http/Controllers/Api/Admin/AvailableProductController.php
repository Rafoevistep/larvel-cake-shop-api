<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvailableProduct;
use App\Models\Product;
use Darryldecode\Cart\Validators\Validator;
use Illuminate\Http\Request;

class AvailableProductController extends Controller
{

    public function index(): \Illuminate\Http\JsonResponse
    {
        //Show Avilable Products
        $available = AvailableProduct::where('qty', '>=', 1)->get();

        return response()->json([
            'message' => 'Available in the bakery',
            $available,
        ]);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        //Show Single Avilable Product

        $available = AvailableProduct::find($id);
        if (!$available) {
            return response()->json([
                'message' => 'Product Not Avilble.'
            ], 404);
        }

        // Return Json Response
        return response()->json([
            'product_available' => $available
        ], 200);
    }

    public function store(Request $request, Product $product): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'qty' => 'required|integer',
        ]);

        $available = AvailableProduct::create([
            'product_id' => $product->id,
            'qty' => $request->qty,
        ]);

        return response()->json($available, 200);

        $validator->validated();
    }


    public function destroy(AvailableProduct $product, $id): \Illuminate\Http\JsonResponse
    {
        // Detail
        $product = AvailableProduct::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product Not Found.'
            ], 404);
        }

        // Delete Category
        $product->delete();

        // Return Json Response
        return response()->json([
            'message' => "Avibale Product successfully deleted."
        ], 200);
    }


}

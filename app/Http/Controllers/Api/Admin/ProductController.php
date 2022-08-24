<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductStoreRequest;
use App\Models\AvailableProduct;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // All Product
        $products = Product::all();

        // Return Json Response
        return response()->json([
            'products' => $products,
        ], 200);
    }

    public function store(ProductStoreRequest $request)
    {
        $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();

        // Create Product
        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'image' => $imageName,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        $available = AvailableProduct::create([
            'product_id' => $product->id,
            'qty' => $request->qty
        ]);

        // Save Image in Storage folder
        Storage::disk('public')->put($imageName, file_get_contents($request->image));

        // Return Json Response
        return response()->json([
            'product' => $product,
            'product_available' => $available
        ], 200);
    }

    public function show($id)
    {
        // Product Detail
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product Not Found.'
            ], 404);
        }

        $available = AvailableProduct::find($id);

        // Return Json Response
        return response()->json([
            'product' => $product,
            'product_available' => $available
        ], 200);
    }

    public function update(ProductStoreRequest $request, $id)
    {
        // Find product
        $product = Product::find($id);
        $available = AvailableProduct::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product Not Found.'
            ], 404);
        }

        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->category_id = $request->category_id;

        if ($request->image) {
            // Public storage
            $storage = Storage::disk('public');

            // Old iamge delete
            if ($storage->exists($product->image))
                $storage->delete($product->image);

            // Image name
            $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();
            $product->image = $imageName;

            // Image save in public folder
            $storage->put($imageName, file_get_contents($request->image));
        }

        $available->qty = $request->qty;

        // Update Product
        $product->save();
        $available->save();

        return response()->json([
            $product,
            $available
        ], 200);
    }

    public function destroy($id)
    {
        // Detail
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product Not Found.'
            ], 404);
        }

        // Public storage
        $storage = Storage::disk('public');

        // Iamge delete
        if ($storage->exists($product->image))
            $storage->delete($product->image);

        // Delete Product
        $product->delete();

        // Return Json Response
        return response()->json([
            'message' => "Product successfully deleted."
        ], 200);
    }

    function search($name)
    {
        $result = Product::where('name', 'LIKE', '%' . $name . '%')->get();
        if (count($result)) {
            return Response()->json($result);
        } else {
            return response()->json(['Result' => 'No Data not found'], 404);
        }
    }
}

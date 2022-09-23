<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Product;
use App\Http\Requests\ProductStoreRequest;
use App\Models\AvailableProduct;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        // All Product
        $products = Product::all();

        // Return Json Response
        return response()->json([
            'products' => $products,
        ], 200);
    }

    public function store(ProductStoreRequest $request): JsonResponse
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

    public function show(int $id): JsonResponse
    {
        // Product Detail
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product Not Found.'
            ], 404);
        }

        $available = AvailableProduct::where('product_id', $product->id)->get();

        // Return Json Response
        return response()->json([
            'product' => $product,
            'product_available' => $available
        ], 200);
    }

    public function update(ProductStoreRequest $request, $id): JsonResponse
    {
        // Find product
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product Not Found.'
            ], 404);
        }

//        $available = AvailableProduct::where('product_id', $product->id)->first();

        $available = AvailableProduct::where('product_id', $product->id)->first();
        $available = new AvailableProduct();
        $available->qty = $request->qty;
//        $available->save();
        $available->update($request->all());
        $product->update($request->all());

        if ($request->image) {
            // Public storage
            $storage = Storage::disk('public');

            // Old image delete
            if ($storage->exists($product->image))
                $storage->delete($product->image);

            // Image name
            $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();
            $product->image = $imageName;

            // Image save in public folder
            $storage->put($imageName, file_get_contents($request->image));
        }

        return response()->json([
            'Product Updated Successfully' => $product,
            'Available In Bakery' => $available,
        ], 200);
    }

    public function destroy(int $id): JsonResponse
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

    function search($name): JsonResponse
    {
        $result = Product::where('name', 'LIKE', '%' . $name . '%')->get();
        if (count($result)) {
            return Response()->json($result);
        } else {
            return response()->json(['Result' => 'No Data not found'], 404);
        }
    }

    public function indexAvailableProduct(): JsonResponse
    {
        //Show Avilable Products
        $available = AvailableProduct::where('qty', '>=', 1)->get();

        return response()->json([
            'message' => 'Available in the bakery',
            'Available Products' => $available,
        ]);
    }
}

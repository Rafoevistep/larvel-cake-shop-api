<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\CreateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();

        return response()->json($categories, 200);
    }


    public function show($id)
    {
        // Category Detail
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category Not Found.'
            ], 404);
        }

        // Return Json Response
        return response()->json([
            'categoty' => $category
        ], 200);
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['Error' => 'Enter Min 5 Symbol']);
        }

        $validator->validated();

        $category = new Category;

        $category->name = $request->name;

        if ($category->save()) {
            return response()->json([
                'message' => 'Category Added Succesfully.',
                'Category' => $category
            ], 200);
        }

        return response()->json(['message' => 'Anible Add Catagory']);
    }


    public function update(Request $request, Category $category,$id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
        ]);

        $category = Category::find($id);

        $category->name = $request->name;
        $validator->validated();

        if ($category->save()) {
            return response()->json([
                $category
            ], 200);
        }

        return response()->json(['message' => 'Unable to update category']);
    }


    public function destroy(Category $category, $id)
    {
        // Detail
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Category Not Found.'
            ], 404);
        }

        // Delete Category
        $category->delete();

        // Return Json Response
        return response()->json([
            'message' => "Category successfully deleted."
        ], 200);
    }
}

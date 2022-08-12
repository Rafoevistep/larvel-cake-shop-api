<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\CreateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::all();

        return response()->json([
            $categories
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();

        return response()->json([
            $categories
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new Category;
        $category->name = $request->name;
        $category->parent_id = $request->parent_category ? $request->parent_category : 0;

        if ($category->save() ) {
            return response()->json([
                'message'=>'Category Added Succesfully.',
                $category
            ],200);
        }

            return response()->json(['message' => 'Anible Add Catagory']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
        ]);

        $category->name = $request->name;
        $category->parent_id = $request->parent_category ? $request->parent_category : 0;

        if ($category->save() ) {
            return response()->json([
                $category
            ],200);
        }

        return response()->json(['message' => 'Unable to update category']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($category->delete()) {
            return response()->json(['message' => 'Category deleted succesfully']);
        }

        return response()->json(['message' => 'Unable to delete category']);

    }
}

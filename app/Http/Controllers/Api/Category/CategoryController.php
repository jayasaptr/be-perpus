<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //get all categories from database with pagination and search by name
        $pagination = $request->pagination ?? 5;

        $search = $request->search ?? '';

        $categories = Category::where('name', 'like', '%'.$search.'%')->paginate($pagination);

        //response data categories
        return response()->json(new CategoryResource(true, 'Data Category', $categories), 200);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        //response error validasi
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create data category
        $category = Category::create([
            'name' => $request->name,
        ]);


        //response data category

        return response()->json(new CategoryResource(true, 'Category Created', $category), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //get category by id
        $category = Category::find($id);

        if (!$category) {
            return response()->json(new CategoryResource(false, 'Category Not Found', null), 404);
        }

        //response data category
        return response()->json(new CategoryResource(true, 'Data Category', $category), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(new CategoryResource(false, 'Category Not Found', null), 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json(new CategoryResource(true, 'Category Updated', $category), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(new CategoryResource(false, 'Category Not Found', null), 404);
        }

        $category->delete();

        return response()->json(new CategoryResource(true, 'Category Deleted', null), 200);
    }
}

<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\category;
use App\Models\Tour;

class categoryController extends Controller
{
    //
    public function getcategoty()
    {
        $getcategoty = Category::get();
        $data = [
            'categoty' => $getcategoty,
        ];
        return response()->json(['data' => $data], 200);
    }
    public function createCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:category',
        ], [
            'name.required' => 'The name field is required.',
            'name.unique' => 'The name must be unique.',
        ]);

        $category = Category::create([
            'name' => $data['name'],
        ]);

        return response()->json(['message' => 'Category created successfully'], 200);
    }
    public function edit($id)
    {
        // return $id;
        $category = category::find($id);

        if (!$category) {
            return response()->json(['message' => 'The category cannot be deleted because there are associated tours'], 400);
        }

        $data = [
            'category' => $category,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function update($id, Request $request)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $category->update($validatedData);

        return response()->json(['message' => 'Category updated successfully'], 200);
    }

    public function delete($id)
    {
        $category = category::find($id);

        if (!$category) {
            return response()->json(['message' => 'The category cannot be deleted because there are associated tours'], 400);
        }

        $tour = Tour::where("category_id", $category->id)->first();

        if ($tour) {
            return response()->json([
                'message' => 'Can`t delete because This category has a tour',
            ], 400);
        }

        $category->delete();

        return response()->json(['message' => 'category deleted successfully']);
    }

    public function search(Request $request)
    {
        $query = category::query();

        if ($request->searchName) {
            $query->where('name', 'like', '%' . $request->searchName . '%');
        }

        $results = $query->get();

        $data = [
            'categoty' => $results,
        ];

        return response()->json([
            'data' => $data
        ]);
    }
}

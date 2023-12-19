<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bed_type;
use Illuminate\Validation\Rule;

class bedtypeController extends Controller
{
    //
    public function getbedtype()
    {

        $bed_type = bed_type::get();

        $data = ["bed_type" => $bed_type];

        return response()->json([
            'data' => $data,
        ]);
    }
    public function storebedtype(Request $request)
    {
        // return $request;
        $data = $request->validate([
            'name' => [
                'required',
                Rule::unique('bed_type', 'name'),
            ],
        ]);
        $bed_type = bed_type::create([
            'name' => $data['name'],
        ]);
        return response()->json(['message' => 'bed type created successfully']);
    }
    public function edit($id)
    {
        $bed_type = bed_type::find($id);
        if (!$bed_type) {
            return response()->json(['message' => 'The bed type cannot be edit'], 400);
        }
        $data = [
            'bed_type' => $bed_type,
        ];
        return response()->json(['data' => $data], 200);
    }
    public function update($id, Request $request)
    {
        $bed_type = bed_type::find($id);

        if (!$bed_type) {
            return response()->json(['message' => 'bed type not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $bed_type->update($validatedData);

        return response()->json(['message' => 'bed type updated successfully'], 200);
    }
    public function delete($id)
    {
        try {
            $bed_type = bed_type::find($id);

            $bed_type->delete();

            return response()->json(['message' => 'bed type deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'bed type to delete Places', 'error' => $e->getMessage()]);
        }
    }
    public function search(Request $request)
    {
        // return $request;
        $name = $request->name;
        $query = bed_type::query();
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        $results = $query->get();
        $data = [
            'results' => $results,
        ];

        return response()->json(['data' => $data], 200);
    }
}

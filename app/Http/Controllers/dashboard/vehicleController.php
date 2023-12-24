<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Validation\Rule;

class vehicleController extends Controller
{
    //
    public function getvehicle()
    {
        // return "ok";
        $Vehicle = Vehicle::get();

        $data = ["Vehicle" => $Vehicle];

        return response()->json([
            'data' => $data,
        ]);
    }
    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                Rule::unique('vehicles', 'name'),
            ],
        ]);
        $bed_type = Vehicle::create([
            'name' => $data['name'],
        ]);
        return response()->json(['message' => 'Vehicle created successfully']);
    }
    public function edit($id)
    {
        $Vehicle = Vehicle::find($id);
        if (!$Vehicle) {
            return response()->json(['message' => 'The bed type cannot be edit'], 400);
        }
        $data = [
            'Vehicle' => $Vehicle,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function update($id, Request $request)
    {
        $Vehicle = Vehicle::find($id);

        if (!$Vehicle) {
            return response()->json(['message' => 'Vehicle not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $Vehicle->update($validatedData);

        return response()->json(['message' => 'Vehicle updated successfully'], 200);
    }
    public function delete($id)
    {
        try {
            $Vehicle = Vehicle::find($id);

            $Vehicle->delete();

            return response()->json(['message' => 'Vehicle deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Vehicle to delete Places', 'error' => $e->getMessage()]);
        }
    }

    public function search(Request $request)
    {
        // $Vehicle = Vehicle::find($id);
        $name = $request->namesearch;
        $query = Vehicle::query();
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

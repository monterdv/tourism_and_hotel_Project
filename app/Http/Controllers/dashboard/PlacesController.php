<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Places;
use App\Models\Hotel;
use App\Models\Tour;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PlacesController extends Controller
{
    public function index()
    {
        $Places = Places::select(['*', 'id as key'])->get();
        return response()->json($Places);
    }

    public function store(Request $request)
    {
        // return $request;
        $data = $request->validate([
            'area' => 'required',
            'country' => 'required',
        ]);

        $data['slug'] = Str::slug($data['country']);

        Places::create($data);

        return response()->json([
            'message' => 'Place created successfully',
        ]);
    }

    public function edit($slug)
    {
        $Places = Places::where('slug', $slug)->first();

        return response()->json([
            'Places' => $Places,
        ]);
    }

    public function update(Request $request, $slug)
    {
        $data = $request->validate([
            'area' => 'required',
            'country' => 'required',
        ]);
        // $place = Places::find($slug);
        $place = Places::where('slug', $slug)->first();

        if (!$place) {
            return response()->json(['message' => 'Place not found'], 404);
        }

        $data['slug'] = Str::slug($data['country']);

        $place->update($data);

        return response()->json(['message' => 'Place updated successfully']);
    }

    public function delete($id)
    {
        $Hotel = Hotel::where('place_id', $id)->get();
        $tour = Tour::where('place_id', $id)->get();

        if ($Hotel->isNotEmpty()) {
            return response()->json([
                'message' => 'Can`t delete because This place has a hotel',
            ], 400);
        }
        if ($tour->isNotEmpty()) {
            return response()->json([
                'message' => 'Can`t delete because This place has a tour',
            ], 400);
        }

        $Places = Places::findOrFail($id);

        $Places->delete();

        return response()->json(['message' => 'Places deleted successfully']);
    }
}

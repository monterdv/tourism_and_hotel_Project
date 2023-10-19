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
        $data = $request->validate([
            'area' => 'required',
            'country' => 'required',
            'file' => 'required',
        ], [
            'area.required' => 'area cannot be null',
            'country.required' => 'country cannot be null',
            'file.required' => 'file cannot be null',
        ]);

        $place = Places::where('country', $data['country'])->first();

        if ($place) {
            return response()->json([
                'errors' => [
                    'country' => ['The location is already on the list'],
                ]
            ], 422);
        }

        $data['slug'] = Str::slug($data['country']);

        if ($request->file('file')) {
            $uploadedFile = $request->file('file');
            $destinationPath = public_path('assets/img/places');
            $newFileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();
            $uploadedFile->move($destinationPath, $newFileName);

            $ImgPath = '/assets/img/places/' . $newFileName;
            $data['images'] = $ImgPath;
        }

        Places::create([
            'area' => $data['area'],
            'country' => $data['country'],
            'slug' => $data['slug'],
            'image' => $data['images'],
        ]);

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
            'file' => 'required',

        ], [
            'area.required' => 'area cannot be null',
            'country.required' => 'country cannot be null',
            'file.required' => 'file cannot be null',
        ]);

        $place = Places::where('slug', $slug)->first();

        if (!$place) {
            return response()->json(['message' => 'Place not found'], 404);
        }

        if ($request->file('file')) {
            $uploadedFile = $request->file('file');
            $destinationPath = public_path('assets/img/places');
            $newFileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();
            $uploadedFile->move($destinationPath, $newFileName);

            if ($place->image) {
                $oldImagePath = public_path($place->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $ImgPath = '/assets/img/places/' . $newFileName;
            $data['image'] = $ImgPath;
        } else {
            $data['image'] = $place->image;
        }

        $data['slug'] = Str::slug($data['country']);

        $place->update([
            'area' => $data['area'],
            'country' => $data['country'],
            'slug' => $data['slug'],
            'image' => $data['image'],
        ]);

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

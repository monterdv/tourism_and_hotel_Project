<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Tour_path;
use App\Models\Places;


class homeTourController extends Controller
{
    //

    public function show()
    {
        $placeInland = Tour::whereHas('place', function ($query) {
            $query->where('area', 'domestic');
        })->with('tourPaths')->orderBy('created_at', 'desc')->limit(6)->get();;

        $placeInternational = Tour::whereHas('place', function ($query) {
            $query->where('area', 'international');
        })->with('tourPaths')->orderBy('created_at', 'desc')->limit(9)->get();

        $data = [
            'inland' => $placeInland,
            'international' => $placeInternational
        ];

        return response()->json(['data' => $data]);
    }

    public function tourdetail($slug)
    {
        if ($slug) {
            $tour = Tour::where('slug', $slug)->with(['place', 'tourPaths'])->first();
            $data = [
                'tour' => $tour,
            ];
            return response()->json(['data' => $data]);
        } else {
            return 'ok';
        }
    }
}

<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Tour_path;
use App\Models\Places;
use App\Models\tour_Time;


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
    public function searchTour($search)
    {
        // return $search;
        $places = Places::where('country', 'like', '%' . $search . '%')->first();

        $Tours = [];
        $tour = null;
        if ($places) {
            $tourplaces = Tour::where('place_id', $places->id)->with(['place', 'tourPaths'])->get();
            $Tours[] = $tourplaces;
        }

        $tour = Tour::where('title', 'like', '%' . $search . '%')->with(['place', 'tourPaths'])->get();

        $Tours[] = $tour;
        $Tours = $tour->concat($tourplaces)->unique('id')->values()->all();

        $placeInland = Places::where('area', 'domestic')->get();

        $placeInternational = Places::where('area', 'international')->get();

        $data = [
            'Tours' => $Tours,
            'placeInternational' => $placeInternational,
            'placeInland' => $placeInland,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function tourdetail($slug)
    {
        if ($slug) {
            $tour = Tour::where('slug', $slug)->with(['place', 'tourPaths'])->first();
            $tourTime = tour_Time::select('id as value', 'date as label', 'slots_remaining', 'slots_booked', 'price_adults', 'price_children')->where('tour_id', $tour->id)->get();

            if ($tourTime) {
                foreach ($tourTime as $item) {
                    $originalLabel = $item->label; // Giá trị label ban đầu (YYYY-MM-DD)
                    $formattedLabel = \Carbon\Carbon::parse($originalLabel)->format('d-m-Y'); // Định dạng lại label
                    $item->label = $formattedLabel; // Gán giá trị đã định dạng lại vào label
                }
            }

            $data = [
                'tour' => $tour,
                'tourTime' => $tourTime,
            ];
            return response()->json(['data' => $data]);
        } else {
            return 'ok';
        }
    }
}

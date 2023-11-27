<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Tour_path;
use App\Models\Places;
use App\Models\tour_Time;
use App\Models\category;
use App\Models\area;
use Carbon\Carbon;
use Srmklive\PayPal\Services\PayPal as PayPalClient;


class homeTourController extends Controller
{
    //
    public function show()
    {
        $placeInland = Tour::whereHas('place', function ($query) {
            $query->where('area_id', 1);
        })->orderBy('created_at', 'desc')->limit(9)->get();

        foreach ($placeInland as $item) {
            $image = Tour_path::where('tour_id', $item->id)->first();
            $price = tour_Time::where('tour_id', $item->id)->first();
            $places = Places::where('id', $item->place_id)->first();

            if ($places) {
                $item->placesName = $places->country;
            }

            if ($image) {
                $item->image = $image->path;
            }

            if ($price) {
                $item->price = $price->price_adults;
            }
        }

        $placeInternational = Tour::whereHas('place', function ($query) {
            $query->where('area_id', 2);
        })->orderBy('created_at', 'desc')->limit(9)->get();

        foreach ($placeInternational as $item) {
            $image = Tour_path::where('tour_id', $item->id)->first();
            $price = tour_Time::where('tour_id', $item->id)->first();
            $places = Places::where('id', $item->place_id)->first();

            if ($places) {
                $item->placesName = $places->country;
            }

            if ($image) {
                $item->image = $image->path;
            }

            if ($price) {
                $item->price = $price->price_adults;
            }
        }

        $data = [
            'inland' => $placeInland,
            'international' => $placeInternational
        ];

        return response()->json(['data' => $data]);
    }

    // phân trang
    public function searchTour($search)
    {
        $places = Places::where('country', 'like', '%' . $search . '%')->first();
        $query = Tour::with(['place', 'category']);

        if ($places) {
            $query->orWhere('place_id', $places->id);
        }
        $query->orWhere('title', 'like', '%' . $search . '%');

        // Thực hiện phân trang
        $Tours = $query->paginate(15);

        foreach ($Tours as $item) {
            $image = Tour_path::where('tour_id', $item->id)->first();
            $price = tour_Time::where("tour_id", $item->id)->first();
            $item->placesName = $places->country;

            if ($image) {
                $item->image = $image->path;
            }

            if ($price) {
                $item->price = $price->price_adults;
            }
        }

        $places = Places::select('id as value', 'country as label')->get();
        $category = category::select('id as value', 'name as label')->get();
        $area = area::select('id as value', 'name as label')->get();

        $data = [
            'places' => $places,
            'category' => $category,
            'area' => $area,
            'Tours' => $Tours,
        ];

        return response()->json(['data' => $data], 200);
    }

    public function areaplace(Request $request)
    {
        // return $request;
        if ($request->area_id) {
            $places = Places::where('area_id', $request->area_id)->select('id as value', 'country as label')->get();
        } else {
            $places = Places::select('id as value', 'country as label')->get();
        }

        $data = [
            'places' => $places,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function advancedsearch(Request $request)
    {
        // return $request;
        $title = $request->title;
        $duration_start = $request->duration_start;
        $duration_end = $request->duration_end;
        $Place_id = $request->Place_id;
        $category_id = $request->category_id;
        $price_end = $request->price_end;
        $price_start = $request->price_start;
        $date_start = $request->date_range_start;
        $date_end = $request->date_range_end;
        $amount = $request->amount;

        $query = Tour::with(['place', 'category']);

        if ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        }
        if ($duration_start && $duration_end) {
            $query->whereBetween("duration", [$duration_start, $duration_end]);
        }
        if ($Place_id) {
            $query->where('Place_id', $Place_id);
        }
        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        $queryTime = tour_Time::where("status", "available");
        // $threeDaysFromNow = Carbon::now()->addDays(3)->toDateString();
        // $queryTime->where('date', '>=', $threeDaysFromNow);

        if ($price_start && $price_end) {
            $queryTime->whereBetween("price_adults", [$price_start, $price_end]);
        }

        if ($date_start && $date_end) {
            $queryTime->whereBetween("date", [$date_start, $date_end]);
        }

        if ($amount) {
            $queryTime->where('slots_remaining', '>=', $amount);
        }

        $tourResults = $query->paginate(15);
        $timeResults = $queryTime->get();

        foreach ($tourResults as $item) {
            $image = Tour_path::where('tour_id', $item->id)->first();
            $price = tour_Time::where("tour_id", $item->id)->first();
            $places = Places::where('id', $item->place_id)->first();

            $item->placesName = $places->country;

            if ($image) {
                $item->image = $image->path;
            }

            if ($price) {
                $item->price = $price->price_adults;
            }
        }

        $results = $tourResults->filter(function ($tour) use ($timeResults) {
            return $timeResults->where('tour_id', $tour->id)->isNotEmpty();
        });

        $data = [
            'results' => $results,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function tourdetail($slug)
    {
        if ($slug) {
            $tour = Tour::where('slug', $slug)->with(['place', 'tourPaths'])->first();
            $tourTime = tour_Time::select('id as value', 'date as label', 'slots_remaining', 'slots_booked', 'price_adults', 'price_children')->where('tour_id', $tour->id)->get();
            $tourImg = Tour_path::where("tour_id", $tour->id)->get();
            if ($tourTime) {
                foreach ($tourTime as $item) {
                    $originalLabel = $item->label; // Giá trị label ban đầu (YYYY-MM-DD)
                    $formattedLabel = \Carbon\Carbon::parse($originalLabel)->format('d-m-Y'); // Định dạng lại label
                    $item->label = $formattedLabel; // Gán giá trị đã định dạng lại vào label
                }
            }

            // place_id 
            $tourRelevant = Tour::where('place_id', $tour->place_id)->limit(3)->get();

            foreach ($tourRelevant as $item) {
                $image = Tour_path::where('tour_id', $item->id)->first();
                $price = tour_Time::where('tour_id', $item->id)->first();

                if ($image) {
                    $item->image = $image->path;
                }

                if ($price) {
                    $item->price = $price->price_adults;
                }
            }

            $data = [
                'tour' => $tour,
                'tourTime' => $tourTime,
                'tourRelevant' => $tourRelevant,
                'tourImg' => $tourImg,
            ];
            return response()->json(['data' => $data]);
        } else {
            return 'ok';
        }
    }
}

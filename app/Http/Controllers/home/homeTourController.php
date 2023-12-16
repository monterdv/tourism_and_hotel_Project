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
        $placeInland = Tour::with(['place', 'category'])->whereHas('place', function ($query) {
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

        $placeInternational = Tour::with(['place', 'category'])->whereHas('place', function ($query) {
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
    public function advancedSearch(Request $request)
    {
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

        $query->whereHas('tourTime', function ($q) use ($price_start, $price_end, $date_start, $date_end, $amount) {
            $q->where('status', 'available');

            if ($price_start && $price_end) {
                $q->whereBetween("price_adults", [$price_start, $price_end]);
            }

            if ($date_start && $date_end) {
                $q->whereBetween("date", [$date_start, $date_end]);
            }

            if ($amount) {
                $q->where('slots_remaining', '>=', $amount);
            }
        });

        $tourResults = $query->paginate(15);

        foreach ($tourResults as $item) {
            $image = Tour_path::where('tour_id', $item->id)->first();
            if ($price_start && $price_end) {
                $price = tour_Time::where("tour_id", $item->id)->whereBetween("price_adults", [$price_start, $price_end])->first();
            } else {
                $price = tour_Time::where("tour_id", $item->id)->first();
            }

            if ($image) {
                $item->image = $image->path;
            }

            if ($price) {
                $item->price = $price->price_adults;
            }
        }

        $data = [
            'results' => $tourResults,
        ];

        return response()->json(['data' => $data], 200);
    }


    public function tourdetail($slug)
    {
        if ($slug) {
            $tour = Tour::where('slug', $slug)->with(['place', 'tourPaths', 'vehicle'])->first();
            $tourTime = tour_Time::select('id as value', 'date as label', 'slots_remaining', 'slots_booked', 'price_adults', 'price_children')
                ->where('tour_id', $tour->id)
                ->where('status', 'available')
                ->whereDate('date', '>=', now()->toDateString())
                ->get();

            if ($tourTime) {
                foreach ($tourTime as $item) {
                    $originalLabel = $item->label; // Giá trị label ban đầu (YYYY-MM-DD)
                    $formattedLabel = Carbon::parse($originalLabel)->format('d-m-Y'); // Định dạng lại label
                    $item->label = $formattedLabel; // Gán giá trị đã định dạng lại vào label
                }
            }

            // place_id 
            $tourRelevant = Tour::where('place_id', $tour->place_id)
            ->where('id', '!=', $tour->id)
            ->limit(3)
            ->get();

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
            ];
            return response()->json(['data' => $data]);
        } else {
            return 'ok';
        }
    }
}

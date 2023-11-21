<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Tour_path;
use App\Models\Places;
use App\Models\tour_Time;
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
        $currentPage = request('page', 1);

        // Số lượng bản ghi trên mỗi trang
        $perPage = 10;

        $places = Places::where('country', 'like', '%' . $search . '%')->first();

        $query = Tour::with(['place']);

        if ($places) {
            $query->orWhere('place_id', $places->id);
        }

        $query->orWhere('title', 'like', '%' . $search . '%');

        // Thực hiện phân trang
        // $Tours = $query->paginate($perPage, ['*'], 'page', $currentPage);
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

        $placeInland = Places::where('area_id', 1)->get();
        $placeInternational = Places::where('area_id', 2)->get();

        $data = [
            'placeInternational' => $placeInternational,
            'placeInland' => $placeInland,
            'Tours' => $Tours,
        ];

        return response()->json(['data' => $data], 200);
    }

    public function searchByPlace($country)
    {
        // return $country;
        $place = Places::where('country', $country)->first();

        if (!$place) {
            return response()->json(['message' => 'Địa điểm không tồn tại'], 404);
        }

        $tours = Tour::where('place_id', $place->id)->with(['place', 'tourPaths'])->get();

        return response()->json(['data' => $tours], 200);
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

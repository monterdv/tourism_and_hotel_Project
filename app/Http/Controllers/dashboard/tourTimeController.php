<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\tour_Time;
use App\Models\Tour;
use Illuminate\Support\Str;

class tourTimeController extends Controller
{
    public function getTourtime($slug)
    {
        $tour = Tour::where('slug', $slug)->first();

        if (!$tour) {
            return response()->json(['message' => 'The tour does not exist'], 400);
        }

        $timeTour = tour_Time::where('tour_id', $tour->id)->get();

        $data = ['timeTour' => $timeTour, 'tour' => $tour];

        return response()->json([
            'data' => $data
        ]);
    }

    public function getCreateTourTime($slug)
    {
        $tour = Tour::where('slug', $slug)->first();

        if (!$tour) {
            return response()->json(['message' => 'The tour does not exist'], 400);
        }

        $data = ['tour' => $tour];

        return response()->json([
            'data' => $data
        ]);
    }

    public function storeTourTimes(Request $request, $slug)
    {
        $tour = Tour::where('slug', $slug)->first();

        if (!$tour) {
            return response()->json(['message' => 'The tour does not exist'], 400);
        }

        $data = $request->validate([
            'status' => 'required',
            'slots_remaining' => 'required',
            'price_adults' => 'required',
            'price_children' => 'required',
            'date' => 'required',
        ], [
            'status.required' => 'Please enter your status',
            'slots_remaining.required' => 'Please enter your slots remaining',
            'price_adults.required' => 'Please enter your price adults',
            'price_children.required' => 'Please enter your price children',
            'date.required' => 'Please enter date',
        ]);

        $data['tour_id'] = $tour->id;

        $code = $this->generateUniqueTourCode();
        $data['Time_Code'] = $code;

        tour_Time::create($data);
        return response()->json(['message' => 'created successfully!']);
    }

    public function generateUniqueTourCode()
    {
        do {
            $letters = "TT";
            $digits = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $code = $letters . $digits;
        } while (tour_Time::where('Time_Code', $code)->exists());

        return $code;
    }

    public function getEditTourTime($slug, $id)
    {
        $tour = Tour::where('slug', $slug)->first();

        if (!$tour) {
            return response()->json(['message' => 'The tour does not exist'], 400);
        }

        $time = tour_Time::where('id', $id)->first();

        if (!$time) {
            return response()->json(['message' => 'The time does not exist'], 400);
        }

        $data = ['time' => $time];

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateTourTime(Request $request, $slug, $id)
    {
        $tour = Tour::where('slug', $slug)->first();

        if (!$tour) {
            return response()->json(['message' => 'The tour does not exist'], 400);
        }

        $time = tour_Time::where('id', $id)->first();

        if (!$time) {
            return response()->json(['message' => 'The time does not exist'], 400);
        }

        $data = $request->validate([
            'status' => 'required',
            'slots_remaining' => 'required',
            'price_adults' => 'required',
            'price_children' => 'required',
            'date' => 'required',
        ], [
            'status.required' => 'Please enter your status',
            'slots_remaining.required' => 'Please enter your slots remaining',
            'price_adults.required' => 'Please enter your price adults',
            'price_children.required' => 'Please enter your price children',
            'date.required' => 'Please enter date',
        ]);

        $time->update($data);
        return response()->json(['message' => 'updated successfully']);
    }

    public function deleteTourTime($slug, $id)
    {
        $tour = Tour::where('slug', $slug)->first();

        if (!$tour) {
            return response()->json(['message' => 'The tour does not exist'], 400);
        }

        $time = tour_Time::where('id', $id)->first();

        if (!$time) {
            return response()->json(['message' => 'The time does not exist'], 400);
        }
        $time->delete();
        return response()->json(['message' => 'deleted successfully']);
    }

    public function search($slug, Request $request)
    {
        // return $request;
        $tour = Tour::where('slug', $slug)->first();

        if (!$tour) {
            return response()->json(['message' => 'The tour does not exist'], 400);
        }

        $query = tour_Time::where('tour_id', $tour->id);

        if ($request->searchDate_start && $request->searchDate_end) {
            $searchDateStart = $request->searchDate_start;
            $searchDateEnd = $request->searchDate_end;

            $query->whereBetween("date", [$searchDateStart, $searchDateEnd]);
        }

        if ($request->has('searchStatus')) {
            $query->where('status', $request->input('searchStatus'));
        }

        $timeTour = $query->get();

        $data = ['timeTour' => $timeTour];
        return response()->json([
            'data' => $data
        ]);
    }
}

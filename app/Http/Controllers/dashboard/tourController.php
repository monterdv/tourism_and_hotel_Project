<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Places;
use App\Models\Tour;
use App\Models\Tour_path;
use App\Models\tour_Time;
use Illuminate\Support\Str;



class tourController extends Controller
{
    //tour
    public function getTours()
    {
        // $tours = Tour::all();
        // $data = ['tours' => $tours];
        // return response()->json([
        //     'data' => $data
        // ]);

        $tours = Tour::select(['*', 'id as key'])->get();

        // Lấy tất cả các đường dẫn hình ảnh cho các tour
        $paths = Tour_path::whereIn('tour_id', $tours->pluck('id'))->get();

        // Gom dữ liệu tour và đường dẫn hình ảnh vào một mảng dữ liệu
        $tourData = [];
        foreach ($tours as $tour) {
            $tourPaths = $paths->where('tour_id', $tour->id)->pluck('path')->toArray();

            // Thêm thông tin về danh sách các đường dẫn hình ảnh vào tour hiện tại
            $tour->paths = $tourPaths;

            // Thêm tour vào mảng $tourData
            $tourData[] = $tour;
        }

        $data = ['tours' => $tourData];

        return response()->json([
            'data' => $data
        ]);
    }
    public function getCreateTour()
    {
        $palces = Places::select('id as value', 'country as label')->get();

        $data = ['places' => $palces];
        return response()->json([
            'data' => $data
        ]);
    }

    public function storeTour(Request $request)
    {
        // return $request;
        $data = $request->validate([
            'title' => 'required|unique:App\Models\Tour,title',
            'place_id' => 'required',
            'status' => 'required',
            'introduce' => 'required',
            'schedule' => 'required',
        ], [
            'title.required' => 'Please enter a name',
            'place_id.required' => 'Please select a place',
            'status.required' => 'Please select a status',
            'introduce.required' => 'Please enter introduce',
            'schedule.required' => 'Please enter schedule',
        ]);
        $data['slug'] = Str::slug($data['title']);

        $code = $this->generateUniqueTourCode();
        $data['tour_Code'] = $code;

        $uploadedFiles = []; // Mảng để lưu trữ các tệp đã tải lên

        if ($request->count == 0) {
            return response()->json([
                'errors' => [
                    'image' => ['images cannot null'],
                ]
            ], 422);
        } else {
            for ($i = 0; $i < $request->count; $i++) {
                $file = $request->file("file_$i");

                // Đảm bảo tệp là hợp lệ
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
                    return response()->json(['message' => "Invalid file extension, jpg, jpeg, png, gif"], 400);
                }
            }
            for ($i = 0; $i < $request->count; $i++) {
                $file = $request->file("file_$i");

                $newFileName = uniqid() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('assets/img/Tour');
                $file->move($destinationPath, $newFileName);

                // Thêm thông tin về tệp vào mảng $uploadedFiles
                $uploadedFiles[] = [
                    'path' => '/assets/img/Tour/' . $newFileName,
                ];
            }
        }

        if (empty($uploadedFiles)) {
            // Xử lý lỗi nếu không có tệp nào hợp lệ
            return response()->json(['message' => 'There are no valid files.'], 400);
        }

        $Tour = Tour::create($data);

        foreach ($uploadedFiles as $fileData) {
            // Lưu đường dẫn tệp vào cơ sở dữ liệu với hotel_id được lấy từ khách sạn đã tạo
            $imageData = [
                'tour_id' => $Tour->id,
                'path' => $fileData['path'],
            ];

            // Tạo bản ghi hình ảnh
            Tour_path::create($imageData);
        }

        return response()->json(['message' => 'The Tour has been created successfully!']);
    }


    public function generateUniqueTourCode()
    {
        do {
            $letters = "TO";
            $digits = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $code = $letters . $digits;
        } while (Tour::where('tour_Code', $code)->exists());

        return $code;
    }

    public function getEditTour($slug)
    {
        $tour = Tour::where('slug', $slug)->first();

        if (!$tour) {
            return response()->json(['message' => 'The tour does not exist'], 400);
        }

        $palces = Places::select('id as value', 'country as label')->get();

        $path = Tour_path::where('tour_id', $tour->id)->select('path as url')
            ->get();;

        $data = ['tour' => $tour, 'places' => $palces, 'path' => $path];

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateTour(Request $request, $slug)
    {
        // return $request;
        $data = $request->validate([
            'title' => 'required',
            'place_id' => 'required',
            'status' => 'required',
            'introduce' => 'required',
            'schedule' => 'required',
        ], [
            'title.required' => 'Please enter a name',
            'place_id.required' => 'Please select a place',
            'status.required' => 'Please select a status',
            'introduce.required' => 'Please enter introduce',
            'schedule.required' => 'Please enter schedule',
        ]);
        $data['slug'] = Str::slug($data['title']);

        $tour = Tour::where('slug', $slug)->first();

        if (!$tour) {
            return response()->json(['message' => 'Tour not found',], 404);
        }

        $foundDuplicateTitle = false;

        $hotelsWithSameTitle = Tour::where('title', $data['title'])->where('id', '!=', $tour->id)->get();
        foreach ($hotelsWithSameTitle as $check) {
            if ($check->slug == $slug) {
                continue;
            }
            $foundDuplicateTitle = true;
            break;
        }

        if ($foundDuplicateTitle) {
            return response()->json(['message' => 'This name is already in use.'], 400);
        }

        $uploadedFiles = []; // Mảng để lưu trữ các tệp đã tải lên

        if ($request->counOld == 0 && $request->countNew == 0) {
            return response()->json(['message' => 'Please select Images, Images cannot null'], 400);
        }

        if ($request->countNew > 0) {
            for ($i = 0; $i < $request->countNew; $i++) {
                $file = $request->file("file_$i");
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
                    throw new \Exception('Invalid file extension.');
                }
            }
            for ($i = 0; $i < $request->countNew; $i++) {
                $file = $request->file("file_$i");

                $newFileName = uniqid() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('assets/img/Tour');
                $file->move($destinationPath, $newFileName);

                $uploadedFiles[] = [
                    'path' => '/assets/img/Tour/' . $newFileName,
                ];
            }
        }

        $pathsToKeep = [];

        for ($i = 0; $i < $request->counOld; $i++) {
            $img = $request->{"Old_" . $i};
            $path = Tour_path::where("path", $img)->first();
            if ($path) {
                $pathsToKeep[] = $path->path;
            } else {
                if (file_exists(public_path($img->path))) {
                    unlink(public_path($img->path));
                }
            }
        }

        $deletedPaths = Tour_path::where('tour_id', $tour->id)
            ->whereNotIn('path', $pathsToKeep)
            ->get();

        foreach ($deletedPaths as $deletedPath) {
            if (file_exists(public_path($deletedPath->path))) {
                unlink(public_path($deletedPath->path));
            }

            $deletedPath->delete();
        }

        foreach ($uploadedFiles as $fileData) {
            $imageData = [
                'tour_id' => $tour->id,
                'path' => $fileData['path'],
            ];

            Tour_path::create($imageData);
        }
        $tour->update([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'introduce' => $data['introduce'],
            'status' => $data['status'],
            'schedule' => $data['schedule'],
            'place_id' => $data['place_id'],
        ]);
        return response()->json(['message' => 'The Tour has been successfully updated']);
    }

    //time
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

        tour_Time::create($data);
        return response()->json(['message' => 'created successfully!']);
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
}

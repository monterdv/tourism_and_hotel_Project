<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Places;
use App\Models\Tour;
use App\Models\Tour_path;
use App\Models\tour_Time;
use App\Models\category;
use App\Models\Vehicle;
use Illuminate\Support\Str;


class tourController extends Controller
{
    //tour
    public function getTours()
    {
        $tours = Tour::join('places', 'tours.place_id', '=', 'places.id')
            ->Join('category', 'tours.category_id', '=', 'category.id')
            ->select(['tours.*', 'tours.id as key', 'places.country as placeName', 'category.name as categoryName'])->get();


        $places = Places::select('id as value', 'country as label')->get();
        $category = category::select('id as value', 'name as label')->get();

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

        $data = [
            'tours' => $tourData,
            'places' => $places,
            'category' => $category
        ];

        return response()->json([
            'data' => $data
        ]);
    }
    public function getCreateTour()
    {
        $palces = Places::select('id as value', 'country as label')->get();
        $category = category::select('id as value', 'name as label')->get();
        $Vehicle = Vehicle::select('id as value', 'name as label')->get();

        $data = [
            'Vehicle' => $Vehicle,
            'places' => $palces,
            'category' => $category
        ];
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
            'category_id' => 'required',
            'duration' => 'required',
            'vehicle_id' => 'required',
        ], [
            'title.required' => 'Please enter a name',
            'place_id.required' => 'Please select a place',
            'status.required' => 'Please select a status',
            'introduce.required' => 'Please enter introduce',
            'schedule.required' => 'Please enter schedule',
            'category_id.required' => 'Please enter category',
            'duration.required' => 'Please enter duration',
            'vehicle_id.required' => 'Please enter vehicle',
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

    //tao ma random tour
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
        $category = category::select('id as value', 'name as label')->get();
        $Vehicle = Vehicle::select('id as value', 'name as label')->get();


        $path = Tour_path::where('tour_id', $tour->id)->select('path as url')
            ->get();;

        $data = [
            'tour' => $tour,
            'Vehicle' => $Vehicle,
            'places' => $palces,
            'path' => $path,
            'category' => $category
        ];

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
            'category_id' => 'required',
            'duration' => 'required',
            'vehicle_id' => 'required',
        ], [
            'title.required' => 'Please enter a name',
            'place_id.required' => 'Please select a place',
            'status.required' => 'Please select a status',
            'introduce.required' => 'Please enter introduce',
            'schedule.required' => 'Please enter schedule',
            'category_id.required' => 'Please enter category',
            'duration.required' => 'Please enter duration',
            'vehicle_id.required' => 'Please enter duration',
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
            return response()->json([
                'errors' => [
                    'image' => ['Please choose Images, Images cannot null']
                ]
            ], 422);
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
            'category_id' => $data['category_id'],
            'duration' => $data['duration'],
            'vehicle_id' => $data['vehicle_id'],
        ]);
        return response()->json(['message' => 'The Tour has been successfully updated']);
    }

    public function delete($id)
    {
        $Tour = Tour::find($id);

        $tourPaths = Tour_path::where('tour_id', $Tour->id)->get();

        $tourtime = tour_Time::where('tour_id', $Tour->id)->get();

        if ($tourtime) {
            return response()->json(['message' => 'The tour cannot be canceled because there is a list of tour times'], 400);
        }

        if ($tourPaths) {
            foreach ($tourPaths as $item) {
                $path = public_path($item->path);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            Tour_path::where('tour_id', $Tour->id)->delete();
        }

        $Tour->delete();

        return response()->json(['message' => 'Tour deleted successfully']);
    }
    public function prominent($id)
    {
        $Tour = Tour::find($id);

        if (!$Tour) {
            return response()->json(['message' => 'Place not found.'], 404);
        }

        $Tour->prominent = !$Tour->prominent;

        $Tour->save();
        return response()->json(['message' => 'The prominent status of the place has been successfully updated.']);
    }
    public function search(Request $request)
    {
        // return $request;
        // Khởi tạo truy vấn bảng 'tours' kèm thông tin của bảng 'places'
        $query = Tour::join('places', 'tours.place_id', '=', 'places.id')
            ->Join('category', 'tours.category_id', '=', 'category.id')
            ->select(['tours.*', 'tours.id as key', 'places.country as placeName', 'category.name as categoryName']);

        // Kiểm tra và thêm điều kiện tìm kiếm nếu có
        if ($request->has('searchName')) {
            $query->where('tours.title', 'like', '%' . $request->searchName . '%');
        }
        if ($request->has('searchPlace_id')) {
            $query->where('tours.place_id', $request->searchPlace_id);
        }
        if ($request->has('searchStatus')) {
            $query->where('tours.status', $request->searchStatus);
        }

        if ($request->has('searchcategory_id')) {
            $query->where('tours.category_id', $request->searchcategory_id);
        }

        // Thực hiện truy vấn và lấy kết quả
        $results = $query->get();

        // Lấy tất cả các đường dẫn hình ảnh cho các tour
        $tourIds = $results->pluck('id');
        $paths = Tour_path::whereIn('tour_id', $tourIds)->get();

        // Gom dữ liệu tour và đường dẫn hình ảnh vào một mảng dữ liệu
        $tourData = [];
        foreach ($results as $tour) {
            $tourPaths = $paths->where('tour_id', $tour->id)->pluck('path')->toArray();

            // Thêm thông tin về danh sách các đường dẫn hình ảnh vào tour hiện tại
            $tour->paths = $tourPaths;

            // Thêm tour vào mảng $tourData
            $tourData[] = $tour;
        }

        $data = [
            'tours' => $tourData,
        ];

        return response()->json([
            'data' => $data
        ]);
    }
}

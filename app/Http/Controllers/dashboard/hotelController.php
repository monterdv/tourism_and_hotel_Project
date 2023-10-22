<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Places;
use Illuminate\Support\Str;
use App\Models\Hotel;
use App\Models\hotel_paths;
use App\Models\Room;
use App\Models\Room_widgets;
use Illuminate\Support\Facades\DB;

class hotelController extends Controller
{
    //hotel
    public function index()
    {
        $hotels = Hotel::select(['*', 'id as key'])->get();

        $places = Places::select('id as value', 'country as label')->get();

        $paths = hotel_paths::whereIn('hotel_id', $hotels->pluck('id'))->get();

        $HotelData = [];
        foreach ($hotels as $hotel) {
            $hotelPaths = $paths->where('hotel_id', $hotel->id)->pluck('path')->toArray();
            $hotelplace = Places::where('id', $hotel->place_id)->first();

            $hotel->place = $hotelplace;
            $hotel->paths = $hotelPaths;

            $HotelData[] = $hotel;
        }

        $data = [
            "hotels" => $HotelData,
            "places" => $places
        ];

        return response()->json([
            'data' => $data,
        ]);
    }

    public function search(Request $request)
    {
        $query = Hotel::select(['*', 'id as key']);

        // Kiểm tra và thêm điều kiện tìm kiếm nếu có
        if ($request->has('searchName')) {
            $query->where('title', 'like', '%' . $request->searchName . '%');
        }
        if ($request->has('searchPlace_id')) {
            $query->where('place_id', $request->searchPlace_id);
        }
        if ($request->has('searchStatus')) {
            $query->where('status', $request->searchStatus);
        }

        // Thực hiện truy vấn và lấy kết quả
        $results = $query->get();

        $HotelData = $results->map(function ($hotel) {
            $hotelPaths = hotel_paths::where('hotel_id', $hotel->id)->pluck('path')->toArray();
            $hotelplace = Places::where('id', $hotel->place_id)->first();

            $hotel->place = $hotelplace;
            $hotel->paths = $hotelPaths;

            return $hotel;
        });

        $data = [
            'hotels' => $HotelData,
        ];

        return response()->json([
            'data' => $data
        ]);
    }


    public function createHotel()
    {
        $places = Places::select('id as value', 'country as label')->get();
        $data = ["places" => $places];
        return response()->json([
            'data' => $data,
        ]);
    }

    // image check file
    public function upload(Request $request)
    {
        try {
            $uploadedFile = $request->file('file');
            $filename = $uploadedFile->getClientOriginalName(); // Lấy tên tệp tin gốc

            // Kiểm tra MIME type hoặc đuôi mở rộng của tệp tin
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Các đuôi mở rộng hình ảnh được cho phép
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif']; // Các MIME type hình ảnh được cho phép

            if ($uploadedFile->isValid() && in_array($uploadedFile->getClientOriginalExtension(), $allowedExtensions) && in_array($uploadedFile->getClientMimeType(), $allowedMimeTypes)) {
                // Đây là một tệp tin hình ảnh hợp lệ
                return response()->json(['status' => 'success',]);
            } else {
                // Đây không phải là tệp tin hình ảnh hợp lệ
                return response()->json([
                    'errors' => [
                        'image' => ['The file is not a valid image']
                    ]
                ], 405);
            }
        } catch (\Exception $e) {
            // Xử lý ngoại lệ (ví dụ: lỗi tải tệp tin)
            return response()->json(['response' => $e->getMessage()]);
        }
    }
    public function storeHotel(Request $request)
    {
        // Xác thực dữ liệu yêu cầu
        $data = $request->validate([
            'title' => 'required|unique:App\Models\Hotel,title',
            'place_id' => 'required',
            'address' => 'required',
            'status' => 'required',
            'introduce' => 'required',
            'star_rating' => 'required',
            'checkin_time' => 'required',
            'checkout_time' => 'required',
        ], [
            'title.required' => 'Please enter a name',
            'place_id.required' => 'Please select a place',
            'address.required' => 'Please enter your address',
            'status.required' => 'Please select a status',
            'introduce.required' => 'Please enter introduce',
            'checkin_time.required' => 'check in time not null',
            'checkout_time.required' => 'check out time not null',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $uploadedFiles = []; // Mảng để lưu trữ các tệp đã tải lên

        if ($request->count == 0) {
            return response()->json([
                'errors' => [
                    'image' => ['image not null']
                ]
            ], 422);
        } else {
            for ($i = 0; $i < $request->count; $i++) {
                $file = $request->file("file_$i");

                // Đảm bảo tệp là hợp lệ
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
                    return response()->json([
                        'errors' => [
                            'image' => ['Invalid file extension, jpg, jpeg, png, gif']
                        ]
                    ], 422);
                }
            }
            for ($i = 0; $i < $request->count; $i++) {
                $file = $request->file("file_$i");

                $newFileName = uniqid() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('assets/img/Hotel');
                $file->move($destinationPath, $newFileName);

                // Thêm thông tin về tệp vào mảng $uploadedFiles
                $uploadedFiles[] = [
                    'path' => '/assets/img/Hotel/' . $newFileName,
                ];
            }
        }

        if (empty($uploadedFiles)) {
            // Xử lý lỗi nếu không có tệp nào hợp lệ
            return response()->json(['message' => 'There are no valid files.'], 400);
        }

        $hotel = Hotel::create($data);

        foreach ($uploadedFiles as $fileData) {
            // Lưu đường dẫn tệp vào cơ sở dữ liệu với hotel_id được lấy từ khách sạn đã tạo
            $imageData = [
                'hotel_id' => $hotel->id,
                'path' => $fileData['path'],
            ];

            // Tạo bản ghi hình ảnh
            hotel_paths::create($imageData);
        }

        return response()->json(['message' => 'The hotel has been created successfully!']);
    }
    public function editHotel($slug)
    {
        $hotel = Hotel::where('hotels.slug', $slug)
            ->join('places', 'hotels.place_id', '=', 'places.id')
            ->select('hotels.*', 'places.country as places')
            ->first();

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found',], 404);
        }

        $path = hotel_paths::where('hotel_id', $hotel->id)
            ->select('path as url')
            ->get();

        $places = Places::select('id as value', 'country as label')->get();
        $data = ["hotel" => $hotel, "places" => $places, "path" => $path,];
        return response()->json([
            'data' => $data,
        ]);
    }
    public function updateHotel(Request $request, $slug)
    {
        $data = $request->validate([
            'title' => 'required',
            'place_id' => 'required',
            'address' => 'required',
            'status' => 'required',
            'introduce' => 'required',
            'star_rating' => 'required',
            'checkin_time' => 'required',
            'checkout_time' => 'required',
        ], [
            'title.required' => 'Please enter a name',
            'place_id.required' => 'Please select a place',
            'address.required' => 'Please enter your address',
            'status.required' => 'Please select a status',
            'introduce.required' => 'Please enter introduce',
            'checkin_time.required' => 'check in time not null',
            'checkout_time.required' => 'check out time not null',
        ]);

        $data['slug'] = Str::slug($data['title']);

        $hotel = Hotel::where('slug', $slug)->first();

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found',], 404);
        }

        $foundDuplicateTitle = false;
        $hotelsWithSameTitle = Hotel::where('title', $data['title'])->where('id', '!=', $hotel->id)->get();
        foreach ($hotelsWithSameTitle as $check) {
            if ($check->slug == $slug) {
                continue;
            }
            $foundDuplicateTitle = true;
            break;
        }

        if ($foundDuplicateTitle) {
            return response()->json([
                'errors' => [
                    'title' => ['This name is already in use.']
                ],
            ], 422);
        }

        $uploadedFiles = []; // Mảng để lưu trữ các tệp đã tải lên

        if ($request->counOld == 0 && $request->countNew == 0) {
            // return response()->json(['message' => 'Please select Images, Images cannot null'], 422);
            return response()->json([
                'errors' => [
                    'image' => ['Please chooce Images, Images cannot null']
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
                $destinationPath = public_path('assets/img/Hotel');
                $file->move($destinationPath, $newFileName);

                $uploadedFiles[] = [
                    'path' => '/assets/img/Hotel/' . $newFileName,
                ];
            }
        }

        $pathsToKeep = [];

        for ($i = 0; $i < $request->counOld; $i++) {
            $img = $request->{"Old_" . $i};
            $path = hotel_paths::where("path", $img)->first();
            if ($path) {
                $pathsToKeep[] = $path->path;
            }
        }

        hotel_paths::where('hotel_id', $hotel->id)
            ->whereNotIn('path', $pathsToKeep)
            ->delete();

        $oldImages = hotel_paths::where('hotel_id', $hotel->id)->get();
        foreach ($oldImages as $oldImage) {
            if (!in_array($oldImage->path, $pathsToKeep)) {
                $oldImagePath = public_path($oldImage->path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $oldImage->delete();
            }
        }

        foreach ($uploadedFiles as $fileData) {
            $imageData = [
                'hotel_id' => $hotel->id,
                'path' => $fileData['path'],
            ];

            hotel_paths::create($imageData);
        }

        $hotel->update([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'star_rating' => $data['star_rating'],
            'introduce' => $data['introduce'],
            'country' => $request->input('country'),
            'address' => $data['address'],
            'status' => $data['status'],
            'place_id' => $data['place_id'],
            'checkout_time' => $data['checkout_time'],
            'checkin_time' => $data['checkin_time'],
        ]);
        return response()->json(['message' => 'The hotel has been successfully updated']);
    }

    public function deleteHotel($id)
    {
        try {
            $hotel = Hotel::findOrFail($id);
            // Lấy danh sách các hình ảnh liên quan đến khách sạn
            $hotelPaths = DB::table('hotel_paths')->where('hotel_id', $hotel->id)->get();

            $rooms = Room::where('hotel_id', $hotel->id)->get();

            foreach ($rooms as $room) {
                // Xóa Room_widgets cho từng phòng
                Room_widgets::where('room_type_id', $room->id)->delete();

                // Kiểm tra và xóa hình ảnh của phòng nếu tồn tại
                if (file_exists(public_path($room->image))) {
                    unlink(public_path($room->image));
                }

                // Xóa phòng
                $room->delete();
            }

            if ($hotelPaths) {
                // Xóa các tệp ảnh liên quan đến khách sạn
                foreach ($hotelPaths as $hotelPath) {
                    $path = public_path($hotelPath->path);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
                hotel_paths::where('hotel_id', $hotel->id)->delete();
            }

            // Xóa khách sạn
            $hotel->delete();

            return response()->json(['message' => 'Hotel deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete Hotel', 'errors' => $e->getMessage()]);
        }
    }

   
}

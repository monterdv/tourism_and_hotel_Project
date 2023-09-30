<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Widget;
use App\Models\Places;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Hotel;
use App\Models\hotel_paths;
use App\Models\Room;
use App\Models\Room_widgets;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CustomException;

use function Laravel\Prompts\select;

class hotelController extends Controller
{
    //hotel
    public function index(Request $request)
    {
        if ($request->name) {
            $hotels = Hotel::where('country', 'like', "%$request->name%")->select(['*', 'id as key'])
                ->get();
        } else {
            $hotels = Hotel::select(['*', 'id as key'])->get();
        }

        $paths = hotel_paths::whereIn('hotel_id', $hotels->pluck('id'))->get();

        $HotelData = [];
        foreach ($hotels as $hotel) {
            $hotelPaths = $paths->where('hotel_id', $hotel->id)->pluck('path')->toArray();
            $hotelplace = Places::where('id', $hotel->place_id)->first();

            $hotel->place = $hotelplace;

            $hotel->paths = $hotelPaths;

            $HotelData[] = $hotel;
        }

        $data = ["hotels" => $HotelData];
        return response()->json([
            'data' => $data,
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

    // image
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
                return response()->json(['response' => 'The file is not a valid image.']);
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
        ]);

        $data['slug'] = Str::slug($data['title']);
        $uploadedFiles = []; // Mảng để lưu trữ các tệp đã tải lên

        if ($request->count == 0) {
            return response()->json(['message' => 'Images cannot null'], 400);
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

        // $hotel = Hotel::where('slug', $slug)->with('place')->first();
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

    // Rooms
    public function getRoom($slug)
    {
        $hotel = Hotel::where('slug', $slug)->first();

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found',], 404);
        }
        $rooms = Room::where('hotel_id', $hotel->id)->get();

        $data = ["rooms" => $rooms, "hotel" => $hotel];

        return response()->json([
            'data' => $data,
        ]);
    }

    public function getCreateRoom($slug)
    {
        $slug_hotel = $slug;
        $hotel = Hotel::where('slug', $slug_hotel)->first();

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found',], 404);
        }

        $Widget = Widget::select('id as value', 'name as label')->get();
        $data = ["Widget" => $Widget, "slug" => $slug, "hotel" => $hotel];
        return response()->json([
            'data' => $data,
        ]);
    }

    public function storeRoom(Request $request, $slug)
    {
        $hotel = Hotel::where('slug', $slug)->get();

        if (!$hotel) {
            return response()->json(['message' => 'not found'], 404);
        }

        $data = $request->validate([
            'name' => 'required',
            'status' => 'required',
            'base_price' => 'required',
            'max_adults' => 'required',
            'max_children' => 'required',
            'widgets' => 'required',
            'file' => 'required',
            'room_count' => 'required'
        ], [
            'name.required' => 'Name cannot be null',
            'status.required' => 'Status cannot be null',
            'base_price.required' => 'Base price cannot be null',
            'max_adults.required' => 'Max adults cannot be null',
            'max_children.required' => 'Max children cannot be null',
            'widgets.required' => 'widgets cannot be null',
            'room_count.required' => 'room count cannot be null',
        ]);

        foreach ($hotel as $check) {
            $existingRoom = Room::where('name', $data['name'])->where('hotel_id', $check->id)->first();
            if ($existingRoom) {
                return response()->json(['message' => 'Room with this name already exists'], 400);
            }
        }

        $fieldsToCheck = ['base_price', 'max_adults', 'max_children'];

        foreach ($fieldsToCheck as $field) {
            if (!is_numeric($data[$field])) {
                return response()->json(['message' => ucfirst($field) . ' cannot be null'], 400);
            }
        }

        $slug_hotel = $slug;
        $hotel = Hotel::where('slug', $slug_hotel)->first();

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found',], 404);
        }

        $checkRoom = Room::where('hotel_id', $hotel->id)->get();
        $roomCountToCheck = $data['room_count'];

        foreach ($checkRoom as $room) {
            if ($room->room_count == $roomCountToCheck) {
                return response()->json(['message' => 'room count already exists',], 400);
            }
        }

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $destinationPath = public_path('assets/img/Room');
            $newFileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();
            $uploadedFile->move($destinationPath, $newFileName);

            $ImgPath = '/assets/img/Room/' . $newFileName;
            $data['images'] = $ImgPath;
        } else {
            return response()->json(['error' => 'Image cannot be null']);
        }

        $widgetValues = explode(',', $request->widgets);

        $data['hotel_id'] = $hotel->id;

        $Room = Room::create([
            'name' => $data['name'],
            'status' => $data['status'],
            'hotel_id' => $data['hotel_id'],
            'base_price' => $data['base_price'],
            'max_adults' => $data['max_adults'],
            'max_children' => $data['max_children'],
            'room_count' => $data['room_count'],
            'slug' => Str::slug($data['name']),
            'image' => $data['images'],
        ]);

        foreach ($widgetValues as $widgetValue) {
            Room_widgets::create([
                'room_type_id' => $Room->id,
                'widgets_id' => $widgetValue,
            ]);
        }

        return response()->json(['message' => 'Room created successfully']);
    }

    public function getEditRoom($slug, $slugRoom)
    {
        $hotel = Hotel::where('slug', $slug)->first();

        if (!$hotel) {
            return response()->json(['message' => 'not found'], 404);
        }
        $room = Room::where('slug', $slugRoom)
            ->where('hotel_id', $hotel->id)
            ->first();

        if (!$room) {
            return response()->json(['message' => 'not found'], 404);
        }

        $roomTypeWidgets = DB::table('room_type_widgets')
            ->where("room_type_id", $room->id)
            ->pluck('widgets_id');

        $widgets = Widget::whereIn('id', $roomTypeWidgets)
            ->select('id as value')
            ->get();

        $widgetOptions = Widget::select('id as value', 'name as label')->get();

        $data = [
            "widgetOptions" => $widgetOptions,
            "room" => $room,
            "widgets" => $widgets,
            "hotel" => $hotel->title,
        ];

        return response()->json([
            'data' => $data,
        ]);
    }

    public function updateRoom(Request $request, $slug, $slugRoom)
    {
        $data = $request->validate([
            'name' => 'required',
            'status' => 'required',
            'base_price' => 'required',
            'max_adults' => 'required',
            'max_children' => 'required',
            'widgets' => 'required',
            'room_count' => 'required'
        ], [
            'name.required' => 'Name cannot be null',
            'status.required' => 'Status cannot be null',
            'base_price.required' => 'Base price cannot be null',
            'max_adults.required' => 'Max adults cannot be null',
            'max_children.required' => 'Max children cannot be null',
            'widgets.required' => 'Widgets cannot be null',
            'room_count.required' => 'Room count cannot be null',
        ]);

        // $roomCount = $request->room_count;
        // if (!is_numeric($roomCount)) {
        //     return response()->json(['message' => 'Room count must be a number'], 400);
        // }

        $fieldsToCheck = ['room_count', 'base_price', 'max_adults', 'max_children'];

        foreach ($fieldsToCheck as $field) {
            if (!is_numeric($data[$field])) {
                return response()->json(['message' => ucfirst($field) . ' cannot be null'], 400);
            }
        }

        $hotel = Hotel::where('slug', $slug)->first();

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found']);
        }

        $room = Room::where('slug', $slugRoom)->where('hotel_id', $hotel->id)->first();

        if (!$room) {
            return response()->json(['message' => 'Room not found']);
        }

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $destinationPath = public_path('assets/img/Room');
            $newFileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();
            $uploadedFile->move($destinationPath, $newFileName);

            $ImgPath = '/assets/img/Room/' . $newFileName;

            // Kiểm tra nếu ảnh cũ tồn tại, thì xóa nó
            if (file_exists(public_path($room->image))) {
                unlink(public_path($room->image));
            }

            $room->image = $ImgPath;
        }

        $room->name = $data['name'];
        $room->status = $data['status'];
        $room->base_price = $data['base_price'];
        $room->max_adults = $data['max_adults'];
        $room->max_children = $data['max_children'];
        $room->room_count = $data['room_count'];
        $room->slug = Str::slug($data['name']);

        $room->save();

        if ($request->widgets)

            Room_widgets::where('room_type_id', $room->id)->delete();
        // RoomWidget::where('room_id', $room->id)->delete();

        $widgetValues = explode(',', $request->widgets);
        foreach ($widgetValues as $widgetValue) {
            Room_widgets::create([
                'room_type_id' => $room->id,
                'widgets_id' => $widgetValue,
            ]);
        }

        return response()->json(['message' => 'Room updated successfully']);
    }

    public function deleteRoom($slug, $id)
    {
        $hotel = Hotel::where('slug', $slug)->first();

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found']);
        }

        $room = Room::where('id', $id)->where('hotel_id', $hotel->id)->first();

        if (!$room) {
            return response()->json(['message' => 'Room not found']);
        }

        if (file_exists(public_path($room->image))) {
            unlink(public_path($room->image));
        }

        Room_widgets::where('room_type_id', $room->id)->delete();

        $room->delete();

        return response()->json(['message' => 'Room deleted successfully']);
    }

    // Widget
    public function getWidget(Request $request)
    {
        if ($request->name) {
            $data = Widget::where('name', 'like', "%$request->name%")->get();
            return response()->json([
                'data' => $data,
            ]);
        } else {
            $data = Widget::get();
            return response()->json([
                'data' => $data,
            ]);
        }
    }

    public function storeWidget(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                Rule::unique('widgets', 'name'),
            ],
        ]);

        Widget::create($data);
        return response()->json(['message' => 'Widget created successfully']);
    }

    public function deleteWidget($id)
    {
        try {
            Room_widgets::where('widgets_id', $id)->delete();

            $Places = Widget::findOrFail($id);
            $Places->delete();

            return response()->json(['message' => 'Widget deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Widget to delete Places', 'error' => $e->getMessage()]);
        }
    }
}

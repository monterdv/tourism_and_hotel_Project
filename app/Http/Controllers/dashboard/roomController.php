<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Room_widgets;
use App\Models\Hotel;
use App\Models\Widget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class roomController extends Controller
{
    //
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
        // return $request;
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
                return response()->json([
                    'errors' => [
                        'name' => ['Room with this name already exists']
                    ]
                ], 422);
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
                return response()->json([
                    'errors' => [
                        'room_count' => ['room count already exists']
                    ]
                ], 422);
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
            'room_count' => 'required',
            'file' => 'required',
        ], [
            'name.required' => 'Name cannot be null',
            'status.required' => 'Status cannot be null',
            'base_price.required' => 'Base price cannot be null',
            'max_adults.required' => 'Max adults cannot be null',
            'max_children.required' => 'Max children cannot be null',
            'widgets.required' => 'Widgets cannot be null',
            'room_count.required' => 'Room count cannot be null',
            'file.required' => 'file cannot be null',
        ]);

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

    public function search(Request $request, $slug)
    {
        $hotel = Hotel::where('slug', $slug)->first();

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found',], 404);
        }
        $query = Room::where('hotel_id', $hotel->id);

        if ($request->has('searchName')) {
            $query->where('name', 'like', '%' . $request->searchName . '%');
        }
        if ($request->has('searchStatus')) {
            $query->where('status', $request->searchStatus);
        }

        $rooms = $query->get();

        $data = [
            "rooms" => $rooms
        ];

        return response()->json([
            'data' => $data,
        ]);
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

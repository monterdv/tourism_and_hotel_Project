<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\room_type_amenities;
use App\Models\Hotel;
use App\Models\amenities;
use App\Models\bed_type;
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
        $rooms = Room::with(['bedtype'])->where('hotel_id', $hotel->id)->get();

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

        $amenities = amenities::select('id as value', 'name as label')->get();
        $bed_type = bed_type::select('id as value', 'name as label')->get();
        $data = [
            "amenities" => $amenities,
            "bed_type" => $bed_type,
            "slug" => $slug,
            "hotel" => $hotel
        ];
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
            'amenitie' => 'required',
            'bed_type_id' => 'required',
            'file' => 'required',
        ], [
            'name.required' => 'Name cannot be null',
            'status.required' => 'Status cannot be null',
            'base_price.required' => 'Base price cannot be null',
            'max_adults.required' => 'Max adults cannot be null',
            'max_children.required' => 'Max children cannot be null',
            'amenitie.required' => 'widgets cannot be null',
            'bed_type_id.required' => 'bed type cannot be null',
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

        $slug_hotel = $slug;
        $hotel = Hotel::where('slug', $slug_hotel)->first();

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found',], 404);
        }

        // $checkRoom = Room::where('hotel_id', $hotel->id)->get();
        // $roomCountToCheck = $data['room_count'];
        // foreach ($checkRoom as $room) {
        //     if ($room->room_count == $roomCountToCheck) {
        //         return response()->json([
        //             'errors' => [
        //                 'room_count' => ['room count already exists']
        //             ]
        //         ], 422);
        //     }
        // }

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

        $amenitieValues = explode(',', $request->amenitie);

        $data['hotel_id'] = $hotel->id;

        $Room = Room::create([
            'name' => $data['name'],
            'status' => $data['status'],
            'hotel_id' => $data['hotel_id'],
            'price' => $data['base_price'],
            'max_adults' => $data['max_adults'],
            'max_children' => $data['max_children'],
            'bed_type_id' => $data['bed_type_id'],
            'slug' => Str::slug($data['name']),
            'image' => $data['images'],
        ]);

        foreach ($amenitieValues as $amenitieValue) {
            room_type_amenities::create([
                'room_type_id' => $Room->id,
                'amenities_id' => $amenitieValue,
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

        $roomTypeamenities = DB::table('room_type_amenities')
            ->where("room_type_id", $room->id)
            ->pluck('amenities_id');

        $amenities = amenities::whereIn('id', $roomTypeamenities)
            ->select('id as value')
            ->get();

        $amenitiesOptions = amenities::select('id as value', 'name as label')->get();
        $bed_type = bed_type::select('id as value', 'name as label')->get();

        $data = [
            "amenitiesOptions" => $amenitiesOptions,
            "room" => $room,
            "bed_typeOptions" => $bed_type,
            "amenities" => $amenities,
            "hotel" => $hotel->title,
        ];

        return response()->json([
            'data' => $data,
        ]);
    }

    public function updateRoom(Request $request, $slug, $slugRoom)
    {
        // return $request;
        $data = $request->validate([
            'name' => 'required',
            'status' => 'required',
            'price' => 'required',
            'max_adults' => 'required',
            'max_children' => 'required',
            'amenities' => 'required',
            'bed_type_id' => 'required',
            'file' => 'required',
        ], [
            'name.required' => 'Name cannot be null',
            'status.required' => 'Status cannot be null',
            'price.required' => 'Base price cannot be null',
            'max_adults.required' => 'Max adults cannot be null',
            'max_children.required' => 'Max children cannot be null',
            'amenities.required' => 'amenities cannot be null',
            'bed_type_id.required' => 'bed type cannot be null',
            'file.required' => 'file cannot be null',
        ]);
        // return $request;

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
        $room->slug = Str::slug($data['name']);
        $room->price = $data['price'];
        $room->max_adults = $data['max_adults'];
        $room->max_children = $data['max_children'];
        $room->bed_type_id = $data['bed_type_id'];

        $room->save();

        room_type_amenities::where('room_type_id', $room->id)->delete();

        $amenitiesValues = explode(',', $request->amenities);
        foreach ($amenitiesValues as $amenitiesValue) {
            room_type_amenities::create([
                'room_type_id' => $room->id,
                'amenities_id' => $amenitiesValue,
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

        room_type_amenities::where('room_type_id', $room->id)->delete();

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

    //amenities
    public function getamenitie()
    {
        // return "ok";
        $amenities = amenities::paginate(15);
        $data = [
            'amenities' => $amenities,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function storeamenitie(Request $request)
    {
        // return $request;
        $data = $request->validate([
            'name' => [
                'required',
                Rule::unique('amenities', 'name'),
            ],
        ]);

        $amenities = amenities::create([
            'name' => $data['name'],
        ]);
        return response()->json(['message' => 'amenities created successfully']);
    }

    public function edit($id)
    {
        // return $id;
        $amenities = amenities::find($id);

        if (!$amenities) {
            return response()->json(['message' => 'The amenities cannot be edit'], 400);
        }
        $data = [
            'amenities' => $amenities,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function update($id, Request $request)
    {
        // return $request;
        $amenities = amenities::find($id);

        if (!$amenities) {
            return response()->json(['message' => 'amenities not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $amenities->update($validatedData);

        return response()->json(['message' => 'amenitie updated successfully'], 200);
    }

    public function searchamenitie(Request $request)
    {
        // return $request;
        $query = amenities::query();

        if ($request->searchName) {
            $query->where('name', 'like', '%' . $request->searchName . '%');
        }

        $results = $query->paginate(15);

        $data = [
            'amenities' => $results,
        ];

        return response()->json([
            'data' => $data
        ]);
    }

    public function deleteamenitie($id)
    {
        try {
            // Room_widgets::where('widgets_id', $id)->delete();

            $Places = amenities::findOrFail($id);
            $Places->delete();

            return response()->json(['message' => 'amenities deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'amenities to delete Places', 'error' => $e->getMessage()]);
        }
    }
}

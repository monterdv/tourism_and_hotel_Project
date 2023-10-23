<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Places;
use App\Models\post;
use Illuminate\Support\Str;


class postcontroller extends Controller
{
    //
    public function index()
    {
        $Places = Places::select('id as value', 'country as label')->get();
        $posts = post::join('Places', 'post.place_id', '=', 'Places.id')
            ->select('Places.country as PlacesName', 'post.*', 'post.id as key')
            ->get();

        $data = [
            "Places" => $Places,
            "posts" => $posts,
        ];

        return response()->json($data);
    }

    public function search(Request $request)
    {
        // return $request;
        $query = post::join('Places', 'post.place_id', '=', 'Places.id')
            ->select('Places.country as PlacesName', 'post.*', 'post.id as key');

        if ($request->searchName) {
            $query->where('post.title', 'like', '%' . $request->searchName . '%');
        }
        if ($request->searchPlace_id) {
            $query->where('post.place_id', $request->searchPlace_id);
        }

        $posts = $query->get();

        $data = [
            "posts" => $posts,
        ];

        return response()->json($data);
    }

    public function getCreate()
    {
        $palces = Places::select('id as value', 'country as label')->get();

        $data = ['places' => $palces];
        return response()->json([
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        // return $request;
        $data = $request->validate([
            'title' => 'required',
            'introduce' => 'required',
            'place_id' => 'required',
            'file' => 'required',
        ], [
            'introduce.required' => 'introduce cannot be null',
            'title.required' => 'name cannot be null',
            'place_id.required' => 'place cannot be null',
            'file.required' => 'file cannot be null',
        ]);

        $post = post::where('title', $data['title'])->first();

        if ($post) {
            return response()->json([
                'errors' => [
                    'title' => ['The location is already on the list'],
                ]
            ], 422);
        }

        $data['slug'] = Str::slug($data['title']);

        if ($request->file('file')) {
            $uploadedFile = $request->file('file');
            $destinationPath = public_path('assets/img/post');
            $newFileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();
            $uploadedFile->move($destinationPath, $newFileName);

            $ImgPath = '/assets/img/post/' . $newFileName;
            $data['images'] = $ImgPath;
        }

        post::create([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'introduce' => $data['introduce'],
            'place_id' => $data['place_id'],
            'image' => $data['images'],
        ]);

        return response()->json([
            'message' => 'Place created successfully',
        ]);
    }

    public function getEdit($slug)
    {
        $palces = Places::select('id as value', 'country as label')->get();
        $post = post::where('slug', $slug)->first();
        $data = [
            'post' => $post,
            'palces' => $palces,
        ];
        return response()->json([
            'data' => $data
        ]);
    }

    public function update(Request $request, $slug)
    {
        // return $request;
        $data = $request->validate([
            'title' => 'required',
            'introduce' => 'required',
            'place_id' => 'required',
            'file' => 'required',
        ], [
            'introduce.required' => 'introduce cannot be null',
            'title.required' => 'name cannot be null',
            'place_id.required' => 'place cannot be null',
            'file.required' => 'file cannot be null',
        ]);

        $post = post::where('slug', $slug)->first();

        if (!$post) {
            return response()->json(['message' => 'post not found'], 404);
        }

        $foundDuplicateTitle = false;

        $countryWithSameTitle = post::where('title', $data['title'])->where('id', '!=', $post->id)->get();
        foreach ($countryWithSameTitle as $check) {
            if ($check->slug == $slug) {
                continue;
            }
            $foundDuplicateTitle = true;
            break;
        }

        if ($foundDuplicateTitle) {
            return response()->json([
                'errors' => [
                    'title' => ['The location is already on the list'],
                ]
            ], 422);
        }

        if ($request->file('file')) {
            $uploadedFile = $request->file('file');
            $destinationPath = public_path('assets/img/post');
            $newFileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();
            $uploadedFile->move($destinationPath, $newFileName);

            if ($post->image) {
                $oldImagePath = public_path($post->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $ImgPath = '/assets/img/post/' . $newFileName;
            $data['image'] = $ImgPath;
        } else {
            $data['image'] = $post->image;
        }

        $data['slug'] = Str::slug($data['title']);

        $post->update([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'place_id' => $data['place_id'],
            'image' => $data['image'],
        ]);

        return response()->json(['message' => 'post updated successfully']);
    }

    public function delete($id)
    {
        $post = post::findOrFail($id);
        if ($post->image) {
            $oldImagePath = public_path($post->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $post->delete();

        return response()->json(['message' => 'post deleted successfully']);
    }
}

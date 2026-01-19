<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'images' => 'nullable|array',
        'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'urls' => 'nullable|array',
        'urls.*' => 'nullable|url',
        'imageable_id' => 'required|integer',
        'imageable_type' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $savedImages = [];

    // 1. حفظ الصور المرفوعة
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $path = $file->store('images', 'public');
            $url = asset('storage/' . $path);

            $image = Image::create([
                'url' => $url,
                'imageable_id' => $request->imageable_id,
                'imageable_type' => $request->imageable_type,
            ]);

            $savedImages[] = $image;
        }
    }

    // 2. حفظ الروابط المباشرة
    if ($request->has('urls')) {
        foreach ($request->urls as $url) {
            $image = Image::create([
                'url' => $url,
                'imageable_id' => $request->imageable_id,
                'imageable_type' => $request->imageable_type,
            ]);

            $savedImages[] = $image;
        }
    }

    return response()->json([
        'message' => 'Images saved successfully',
        'images' => $savedImages
    ], 201);
}



    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        $image->delete();

        return response()->json(['message' => 'Image deleted']);
    }
}

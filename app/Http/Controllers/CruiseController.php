<?php

namespace App\Http\Controllers;

use App\Models\Cruise;
use Illuminate\Http\Request;
 use App\Models\Image;

class CruiseController extends Controller
{
    public function index(Request $request)
{
    $locale = $request->header('Accept-Language') ?? app()->getLocale();

    $cruises = Cruise::with('images', 'reviews')->paginate(10);

    $data = $cruises->getCollection()->map(function ($cruise) use ($locale) {
        return [
            'id' => $cruise->id,
            'name' => $locale === 'ar' ? $cruise->name_ar : $cruise->name_en,
            'description' => $locale === 'ar' ? $cruise->description_ar : $cruise->description_en,
            'location' => $cruise->location,
            'price' => $cruise->price,
            'booking_link' => $cruise->booking_link,
            'rate' => $cruise->rate,
            'from' => $cruise->from,
            'to' => $cruise->to,
            'depart_time' => $cruise->depart_time,
            'return_time' => $cruise->return_time,
            'property_type' => $cruise->property_type,
            'style' => $cruise->style,
            'amenities' => json_decode($cruise->amenities, true), // لو مخزنة كـ JSON
            'reviews_count' => $cruise->reviews->count(),
            'images' => $cruise->images->pluck('url'),
        ];
    });

    return response()->json([
        'current_page' => $cruises->currentPage(),
        'last_page' => $cruises->lastPage(),
        'per_page' => $cruises->perPage(),
        'total' => $cruises->total(),
        'data' => $data,
    ]);
}



public function store(Request $request)
{
    $validated = $request->validate([
        'name_en' => 'required|string',
        'name_ar' => 'required|string',
        'description_en' => 'nullable|string',
        'description_ar' => 'nullable|string',
        'location' => 'required|string',
        'price' => 'required|numeric',
        'booking_link' => 'nullable|url',
        'from' => 'nullable|string',
        'to' => 'nullable|string',
        'depart_time' => 'nullable|date_format:H:i',
        'return_time' => 'nullable|date_format:H:i',
        'property_type' => 'nullable|string',
        'style' => 'nullable|string',
        'rate' => 'nullable|numeric|min:0|max:5',
        'amenities' => 'nullable|array',
        'images.*' => 'nullable|image|max:2048',
        'image_urls' => 'nullable|array',
'image_urls.*' => 'nullable|url',

    ]);

    $validated['amenities'] = isset($validated['amenities']) ? json_encode($validated['amenities']) : null;

    $cruise = Cruise::create($validated);

    // حفظ الصور
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $imageFile) {
            $path = $imageFile->store('cruises', 'public');
            $cruise->images()->create(['url' => "/storage/{$path}"]);
        }
    }

    // حفظ روابط الصور إن وجدت
if ($request->has('image_urls')) {
    foreach ($request->image_urls as $url) {
        $cruise->images()->create(['url' => $url]);
    }
}


    return response()->json($cruise, 201);
}

public function update(Request $request, $id)
{
    $cruise = Cruise::findOrFail($id);

    $validated = $request->validate([
        'name_en' => 'sometimes|required|string',
        'name_ar' => 'sometimes|required|string',
        'description_en' => 'nullable|string',
        'description_ar' => 'nullable|string',
        'location' => 'sometimes|required|string',
        'price' => 'sometimes|required|numeric',
        'booking_link' => 'nullable|url',
        'from' => 'nullable|string',
        'to' => 'nullable|string',
        'depart_time' => 'nullable|date_format:H:i',
        'return_time' => 'nullable|date_format:H:i',
        'property_type' => 'nullable|string',
        'style' => 'nullable|string',
        'rate' => 'nullable|numeric|min:0|max:5',
        'amenities' => 'nullable|array',
        'images.*' => 'nullable|image|max:2048',
        'image_urls' => 'nullable|array',
        'image_urls.*' => 'nullable|url',

    ]);

    $validated['amenities'] = isset($validated['amenities']) ? json_encode($validated['amenities']) : $cruise->amenities;

    $cruise->update($validated);

    // حفظ الصور الجديدة
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $imageFile) {
            $path = $imageFile->store('cruises', 'public');
            $cruise->images()->create(['url' => "/storage/{$path}"]);
        }
    }
    // حفظ روابط الصور إن وجدت
if ($request->has('image_urls')) {
    foreach ($request->image_urls as $url) {
        $cruise->images()->create(['url' => $url]);
    }
}


    return response()->json($cruise);
}


public function show(Request $request, $id)
{
    $locale = $request->header('Accept-Language') ?? app()->getLocale();

    $cruise = Cruise::with('images', 'reviews')->findOrFail($id);

    return response()->json([
        'id' => $cruise->id,
        'name' => $locale == 'ar' ? $cruise->name_ar : $cruise->name_en,
        'description' => $locale == 'ar' ? $cruise->description_ar : $cruise->description_en,
        'location' => $cruise->location,
        'price' => $cruise->price,
        'booking_link' => $cruise->booking_link,
        'rate' => $cruise->rate,
        'from' => $cruise->from,
        'to' => $cruise->to,
        'depart_time' => $cruise->depart_time,
        'return_time' => $cruise->return_time,
        'property_type' => $cruise->property_type,
        'style' => $cruise->style,
        'amenities' => json_decode($cruise->amenities, true),
        'reviews_count' => $cruise->reviews->count(),
        'images' => $cruise->images->pluck('url'),
    ]);
}


// public function update(Request $request, $id)
// {


//     $cruise = Cruise::findOrFail($id);
//     $cruise->update($request->all());

//     return $cruise;
// }

public function destroy($id)
{


    Cruise::findOrFail($id)->delete();
    return response()->json(['message' => 'Deleted']);
}

}

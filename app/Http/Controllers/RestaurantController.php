<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index(Request $request)
{
    $locale = $request->header('Accept-Language') ?? app()->getLocale();

    $restaurants = Restaurant::with('images', 'reviews')->paginate(10);

    $data = $restaurants->getCollection()->map(function ($restaurant) use ($locale) {
        return [
            'id' => $restaurant->id,
            'name' => $locale == 'ar' ? $restaurant->name_ar : $restaurant->name_en,
            'description' => $locale == 'ar' ? $restaurant->description_ar : $restaurant->description_en,
            'location' => $restaurant->location,
            'rate' => $restaurant->rate,
            'property_type' => $restaurant->property_type,
            'cuisine' => $restaurant->cuisine,
            'features' => $restaurant->features,
            'reviews_count' => $restaurant->reviews->count(),
            'booking_link' => $restaurant->booking_link,
            'images' => $restaurant->images->pluck('url'),
        ];
    });

    return response()->json([
        'current_page' => $restaurants->currentPage(),
        'last_page' => $restaurants->lastPage(),
        'per_page' => $restaurants->perPage(),
        'total' => $restaurants->total(),
        'data' => $data,
    ]);
}


public function store(Request $request)
{


    $request->validate([
    'name_en' => 'required|string',
    'name_ar' => 'required|string',
    'description_en' => 'nullable|string',
    'description_ar' => 'nullable|string',
    'location' => 'required|string',
    'rate' => 'nullable|numeric|min:0|max:5',
    'booking_link' => 'nullable|url',
    'property_type' => 'nullable|string|max:255',
    'cuisine' => 'nullable|string|max:255',
    'features' => 'nullable|array',
    'features.*' => 'string',
    'images.*' => 'nullable|image|max:2048',
    'image_urls' => 'nullable|array',
    'image_urls.*' => 'nullable|url',
]);


$restaurant = Restaurant::create($request->all());


  if ($request->hasFile('images')) {
    foreach ($request->file('images') as $imageFile) {
        $path = $imageFile->store('uploads/restaurants', 'public');
        $restaurant->images()->create(['url' => 'storage/' . $path]);
    }
}

if ($request->has('image_urls')) {
    foreach ($request->image_urls as $url) {
        $restaurant->images()->create(['url' => $url]);
    }
}


     return response()->json($restaurant->load('images'), 201);
}

public function show(Request $request, $id)
{
    $locale = $request->header('Accept-Language') ?? app()->getLocale();

    $restaurant = Restaurant::with('images')->findOrFail($id);

    return response()->json([
    'id' => $restaurant->id,
    'name' => $locale == 'ar' ? $restaurant->name_ar : $restaurant->name_en,
    'description' => $locale == 'ar' ? $restaurant->description_ar : $restaurant->description_en,
    'location' => $restaurant->location,
    'rate' => $restaurant->rate,
    'property_type' => $restaurant->property_type,
    'cuisine' => $restaurant->cuisine,
    'features' => $restaurant->features,
    'reviews_count' => $restaurant->reviews->count(),
    'booking_link' => $restaurant->booking_link,
    'images' => $restaurant->images->pluck('url'),
]);

}

public function update(Request $request, $id)
{
    $restaurant = Restaurant::findOrFail($id);

    $validated = $request->validate([
        'name_en' => 'sometimes|required|string',
        'name_ar' => 'sometimes|required|string',
        'description_en' => 'nullable|string',
        'description_ar' => 'nullable|string',
        'location' => 'nullable|string',
        'rate' => 'nullable|numeric|min:0|max:5',
        'booking_link' => 'nullable|url',
        'property_type' => 'nullable|string|max:255',
        'cuisine' => 'nullable|string|max:255',
        'features' => 'nullable|array',
        'features.*' => 'string',
        'images.*' => 'nullable|image|max:2048',
        'image_urls' => 'nullable|array',
        'image_urls.*' => 'nullable|url',
        'old_images' => 'nullable|array',
        'old_images.*' => 'integer|exists:images,id',
    ]);

    $restaurant->update($validated);

    // حذف الصور القديمة غير المرسلة ضمن old_images
    $existingImageIds = $restaurant->images()->pluck('id')->toArray();
    $keepImages = $request->old_images ?? [];
    $deleteImages = array_diff($existingImageIds, $keepImages);

    foreach ($deleteImages as $imageId) {
        $restaurant->images()->where('id', $imageId)->delete();
    }

    // رفع الصور الجديدة من الجهاز
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $imageFile) {
            $path = $imageFile->store('uploads/restaurants', 'public');
            $restaurant->images()->create(['url' => 'storage/' . $path]);
        }
    }

    // إضافة روابط الصور الجديدة من نوع URL
    if ($request->has('image_urls')) {
        foreach ($request->image_urls as $url) {
            $restaurant->images()->create(['url' => $url]);
        }
    }

    return response()->json($restaurant->load('images'));
}


public function destroy($id)
{

    $restaurant = Restaurant::findOrFail($id);
    $restaurant->delete();

    return response()->json(['message' => 'Restaurant deleted']);
}

}

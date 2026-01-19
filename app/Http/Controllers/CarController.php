<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
{
    $cars = Car::with('images', 'reviews')->paginate(10);

    $data = $cars->getCollection()->map(function ($car) {
        return [
            'id' => $car->id,
            'brand' => $car->brand,
            'model' => $car->model,
            'type' => $car->type,
            'year' => $car->year,
            'price' => $car->price,
            'rate' => $car->rate,
            'location' => $car->location,
            'description_en' => $car->description_en,
            'description_ar' => $car->description_ar,
            'booking_link' => $car->booking_link,
            'seats' => $car->seats,
            'large_bag' => $car->large_bag,
            'small_bag' => $car->small_bag,
            'car_specification' => $car->car_specification,
            'supplier' => $car->supplier,
            'reviews_count' => $car->reviews->count(),
            'images' => $car->images->pluck('url'),
        ];
    });

    return response()->json([
        'current_page' => $cars->currentPage(),
        'last_page' => $cars->lastPage(),
        'per_page' => $cars->perPage(),
        'total' => $cars->total(),
        'data' => $data,
    ]);
}


    public function show($id)
{
    $car = Car::with('images', 'reviews')->findOrFail($id);

    return response()->json([
        'id' => $car->id,
        'brand' => $car->brand,
        'model' => $car->model,
        'type' => $car->type,
        'year' => $car->year,
        'price' => $car->price,
        'rate' => $car->rate,
        'location' => $car->location,
        'description_en' => $car->description_en,
        'description_ar' => $car->description_ar,
        'booking_link' => $car->booking_link,
        'seats' => $car->seats,
        'large_bag' => $car->large_bag,
        'small_bag' => $car->small_bag,
        'car_specification' => $car->car_specification,
        'supplier' => $car->supplier,
        'reviews_count' => $car->reviews->count(),
        'images' => $car->images->pluck('url'),
    ]);
}


    public function store(Request $request)
{
    $data = $request->validate([
        'brand' => 'required|string',
        'model' => 'required|string',
        'type' => 'required|string',
        'year' => 'required|integer',
        'price' => 'required|numeric',
        'location' => 'required|string',
        'description_en' => 'nullable|string',
        'description_ar' => 'nullable|string',
        'rate' => 'nullable|numeric|min:0|max:5',
        'booking_link' => 'nullable|url',
        'seats' => 'nullable|integer',
        'large_bag' => 'nullable|integer',
        'small_bag' => 'nullable|integer',
        'car_specification' => 'nullable|string',
        'supplier' => 'nullable|string',
        // 'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048', // new
         'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'image_urls' => 'nullable|array',
        'image_urls.*' => 'nullable|url',
    ]);

    $car = Car::create($data);

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('cars', 'public');
            $car->images()->create([
                'url' => '/storage/' . $path,
            ]);
        }
    }
    // روابط الصور المباشرة
    if ($request->has('image_urls')) {
        foreach ($request->image_urls as $url) {
            $car->images()->create(['url' => $url]);
        }
    }

    return response()->json($car->load('images'));
}


    public function update(Request $request, Car $car)
{
    $data = $request->validate([
        'brand' => 'sometimes|required|string',
        'model' => 'sometimes|required|string',
        'type' => 'sometimes|required|string',
        'year' => 'sometimes|required|integer',
        'price' => 'sometimes|required|numeric',
        'location' => 'sometimes|required|string',
        'description_en' => 'nullable|string',
        'description_ar' => 'nullable|string',
        'rate' => 'nullable|numeric|min:0|max:5',
        'booking_link' => 'nullable|string',
        'seats' => 'nullable|integer',
        'large_bag' => 'nullable|integer',
        'small_bag' => 'nullable|integer',
        'car_specification' => 'nullable|string',
        'supplier' => 'nullable|string',
        'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        'old_images' => 'array', // IDs of images to keep
        'old_images.*' => 'integer|exists:images,id',
        'image_urls' => 'nullable|array',
'image_urls.*' => 'nullable|url',

    ]);

    $car->update($data);

    // حذف الصور القديمة إن لم تكن ضمن old_images
    $existingImageIds = $car->images()->pluck('id')->toArray();
    $keepImages = $request->old_images ?? [];
    $deleteImages = array_diff($existingImageIds, $keepImages);

    foreach ($deleteImages as $id) {
        $car->images()->where('id', $id)->delete();
    }

    // إضافة صور جديدة
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('cars', 'public');
            $car->images()->create([
                'url' => '/storage/' . $path,
            ]);
        }
    }

    // روابط الصور الجديدة
if ($request->has('image_urls')) {
    foreach ($request->image_urls as $url) {
        $car->images()->create(['url' => $url]);
    }
}


    return response()->json($car->load('images'));
}


    public function destroy(Car $car)
    {
        $car->delete();
        return response()->json(['message' => 'Car deleted successfully']);
    }
}

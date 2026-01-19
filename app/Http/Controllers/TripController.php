<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TripController extends Controller
{
   public function index(Request $request)
{
    $locale = $request->header('Accept-Language') ?? app()->getLocale();

    $query = Trip::with('images', 'guide', 'reviews');

    // فلترة حسب النوع
    if ($request->has('type') && $request->type) {
        $query->where('type', $request->type); // تأكد أن لديك عمود "type" في جدول trips
    }

    // فلترة حسب الدولة (country)
    if ($request->has('country') && $request->country) {
    $query->where('location', $request->country); // country هنا تكون string مثل "United Arab Emirates"

    if ($request->has('min_price')) {
    $query->where('price', '>=', $request->min_price);
}
if ($request->has('max_price')) {
    $query->where('price', '<=', $request->max_price);
}

if ($request->has('min_duration')) {
    $query->whereRaw("CAST(SUBSTRING_INDEX(duration, ' ', 1) AS UNSIGNED) >= ?", [$request->min_duration]);
}
if ($request->has('max_duration')) {
    $query->whereRaw("CAST(SUBSTRING_INDEX(duration, ' ', 1) AS UNSIGNED) <= ?", [$request->max_duration]);
}

}


    // فلترة حسب الشريك (partner)


    // فلترة حسب الميزانية (rating = budget)
    if ($request->has('budget') && is_numeric($request->budget)) {
        $query->where('price', '<=', $request->budget);
    }

    $trips = $query->paginate(30);

    $data = $trips->getCollection()->map(function ($trip) use ($locale) {
        return [
            'id' => $trip->id,
            'name' => $locale === 'ar' ? $trip->name_ar : $trip->name_en,
            'description' => $locale === 'ar' ? $trip->description_ar : $trip->description_en,
            'location' => $trip->location,
            'price' => $trip->price,
            'rate' => $trip->rate,
            'duration' => $trip->duration,
            'continent' => $trip->continent,
            'difficulty' => $trip->difficulty,
            'reviews_count' => $trip->reviews->count(),
            'images' => $trip->images->pluck('url'),
            'guide' => [
                'id' => $trip->guide->id,
                'name' => $trip->guide->name,
            ],
        ];
    });

    return response()->json([
        'current_page' => $trips->currentPage(),
        'last_page' => $trips->lastPage(),
        'per_page' => $trips->perPage(),
        'total' => $trips->total(),
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
        'rate' => 'required|numeric|min:0|max:5',
        'booking_link' => 'nullable|url',
        'guide_id' => 'nullable|exists:users,id',
        'duration' => 'nullable|string',
        'continent' => 'nullable|string',
        'difficulty' => 'nullable|in:easy,medium,hard',
        'images.*' => 'nullable|image|max:2048',
        'image_urls' => 'nullable|array',
        'image_urls.*' => 'nullable|url',
    ]);

    // تأكد أن الحقول الاختيارية تكون null عند عدم الإرسال
    $validated['difficulty'] = $validated['difficulty'] ?? null;

    $trip = Trip::create($validated);

    // ✅ رفع الصور من الجهاز
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $imageFile) {
            $path = $imageFile->store('trips', 'public');
            $trip->images()->create(['url' => '/storage/' . $path]);
        }
    }

    // ✅ روابط صور خارجية
    if ($request->has('image_urls')) {
        foreach ($request->image_urls as $url) {
            $trip->images()->create(['url' => $url]);
        }
    }

    return response()->json($trip->load('images'), 201);
}





public function show(Request $request, $id)
{
    $locale = $request->header('Accept-Language') ?? app()->getLocale();

    $trip = Trip::with('images', 'guide', 'reviews')->findOrFail($id);

    return response()->json([
        'id' => $trip->id,
        'name' => $locale === 'ar' ? $trip->name_ar : $trip->name_en,
        'description' => $locale === 'ar' ? $trip->description_ar : $trip->description_en,
        'location' => $trip->location,
        'price' => $trip->price,
        'rate' => $trip->rate,
        'duration' => $trip->duration,
        'continent' => $trip->continent,
        'difficulty' => $trip->difficulty,
        'reviews_count' => $trip->reviews->count(),
        'images' => $trip->images->pluck('url'),
        'guide' => [
            'id' => $trip->guide->id,
            'name' => $trip->guide->name,
        ],
    ]);
}

public function uploadImages(Request $request, $id)
{
    $trip = Trip::findOrFail($id);

    $request->validate([
        'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $uploadedImages = [];

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('trips', 'public');

            $uploadedImages[] = $trip->images()->create([
                'url' => '/storage/' . $path,
            ]);
        }
    }

    return response()->json([
        'message' => 'Images uploaded successfully',
        'images' => $uploadedImages,
    ]);
}

public function update(Request $request, $id)
{
    $trip = Trip::findOrFail($id);

    $validated = $request->validate([
        'name_en' => 'sometimes|required|string',
        'name_ar' => 'sometimes|required|string',
        'description_en' => 'nullable|string',
        'description_ar' => 'nullable|string',
        'location' => 'sometimes|required|string',
        'price' => 'sometimes|required|numeric',
        'rate' => 'sometimes|required|numeric',
        'booking_link' => 'nullable|url',
        'guide_id' => 'nullable|exists:users,id',
        'duration' => 'nullable|string',
        'continent' => 'nullable|string',
        'difficulty' => 'nullable|in:easy,medium,hard',
        'images.*' => 'nullable|image|max:2048',
        'image_urls' => 'nullable|array',
        'image_urls.*' => 'nullable|url',
        'old_images' => 'nullable|array',
        'old_images.*' => 'integer|exists:images,id',
    ]);

    $validated['difficulty'] = $validated['difficulty'] ?? null;

    // تحديث البيانات
    $trip->update($validated);

    // حذف الصور غير المرسلة
    $existingImageIds = $trip->images()->pluck('id')->toArray();
    $keepImages = $request->old_images ?? [];
    $deleteImages = array_diff($existingImageIds, $keepImages);

    foreach ($deleteImages as $imageId) {
        $trip->images()->where('id', $imageId)->delete();
    }

    // رفع صور من الجهاز
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $imageFile) {
            $path = $imageFile->store('trips', 'public');
            $trip->images()->create(['url' => '/storage/' . $path]);
        }
    }

    // روابط صور مباشرة
    if ($request->has('image_urls')) {
        foreach ($request->image_urls as $url) {
            $trip->images()->create(['url' => $url]);
        }
    }

    return response()->json($trip->load('images'));
}




public function destroy($id)
{


    Trip::findOrFail($id)->delete();

    return response()->json(['message' => 'Deleted']);
}

}

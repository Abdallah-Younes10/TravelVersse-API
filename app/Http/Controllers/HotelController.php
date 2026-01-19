<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * عرض قائمة الفنادق مع عرض الاسم والوصف باللغة المطلوبة.
     */
    public function index(Request $request)
{
    $locale = $request->header('Accept-Language') ?? app()->getLocale();

    // تحميل الفنادق مع الصور والمراجعات وتطبيق pagination
    $hotels = Hotel::with('images', 'reviews')->paginate(20);

    // تعديل البيانات قبل الإرجاع
    $data = $hotels->getCollection()->map(function ($hotel) use ($locale) {
        $name = $locale == 'ar' ? ($hotel->name_ar ?? $hotel->name_en) : ($hotel->name_en ?? $hotel->name_ar);
        $description = $locale == 'ar' ? ($hotel->description_ar ?? $hotel->description_en) : ($hotel->description_en ?? $hotel->description_ar);

        return [
            'id' => $hotel->id,
            'name' => $name,
            'description' => $description,
            'location' => $hotel->location,
            'rate' => $hotel->rate,
            'price' => $hotel->price,
            'old_price' => $hotel->old_price,
            'class' => $hotel->class,
            'style' => $hotel->style,
            'amenities' => $hotel->amenities,
            'reviews_count' => $hotel->reviews->count(),
            'booking_link' => $hotel->booking_link,
            'images' => $hotel->images->pluck('url'),
        ];
    });

    // دمج بيانات pagination مع النتائج
    return response()->json([
        'current_page' => $hotels->currentPage(),
        'last_page' => $hotels->lastPage(),
        'per_page' => $hotels->perPage(),
        'total' => $hotels->total(),
        'data' => $data,
    ]);
}


    /**
     * تخزين فندق جديد.
     */
    public function store(Request $request)
    {
        $request->validate([
    'name_en' => 'required|string',
    'name_ar' => 'required|string',
    'description_en' => 'nullable|string',
    'description_ar' => 'nullable|string',
    'rate' => 'nullable|numeric|between:0,5',
    'price' => 'nullable|numeric|min:0',
    'old_price' => 'nullable|numeric|min:0',
    'class' => 'nullable|string|max:255',
    'style' => 'nullable|string|max:255',
    'amenities' => 'nullable|array',
    'amenities.*' => 'string',
    'location' => 'required|string',
    'booking_link' => 'nullable|url',
    'images.*' => 'nullable|image|max:2048',
    // 'images.*' => 'nullable|image|max:2048',
    'image_urls' => 'nullable|array',
    'image_urls.*' => 'nullable|url',
]);

$hotel = Hotel::create($request->all());
if ($request->hasFile('images')) {
        foreach ($request->file('images') as $imageFile) {
            $path = $imageFile->store('uploads/hotels', 'public');
            $hotel->images()->create([
                'url' => 'storage/' . $path,
            ]);
        }
    }

if ($request->has('image_urls')) {
    foreach ($request->image_urls as $url) {
        $hotel->images()->create(['url' => $url]);
    }
}

        return response()->json($hotel, 201); // 201 Created
    }

    /**
     * عرض تفاصيل فندق معين.
     */
    public function show(Request $request, $id)
    {
        $locale = $request->header('Accept-Language') ?? app()->getLocale();

        $hotel = Hotel::with('images')->findOrFail($id);

        $name = $locale == 'ar' ? ($hotel->name_ar ?? $hotel->name_en) : ($hotel->name_en ?? $hotel->name_ar);
        $description = $locale == 'ar' ? ($hotel->description_ar ?? $hotel->description_en) : ($hotel->description_en ?? $hotel->description_ar);

        return response()->json([
            'id' => $hotel->id,
    'name' => $name,
    'description' => $description,
    'location' => $hotel->location,
    'rate' => $hotel->rate,
    'price' => $hotel->price,
    'old_price' => $hotel->old_price,
    'class' => $hotel->class,
    'style' => $hotel->style,
    'amenities' => $hotel->amenities,
    'reviews_count' => $hotel->reviews->count(),
    'booking_link' => $hotel->booking_link,
    'images' => $hotel->images->pluck('url'),
        ]);
    }

    /**
     * تحديث بيانات فندق موجود.
     */
    public function update(Request $request, $id)
{
    $hotel = Hotel::findOrFail($id);

    $validated = $request->validate([
        'name_en' => 'sometimes|required|string',
        'name_ar' => 'sometimes|required|string',
        'description_en' => 'nullable|string',
        'description_ar' => 'nullable|string',
        'rate' => 'nullable|numeric|between:0,5',
        'price' => 'nullable|numeric|min:0',
        'old_price' => 'nullable|numeric|min:0',
        'class' => 'nullable|string|max:255',
        'style' => 'nullable|string|max:255',
        'amenities' => 'nullable|array',
        'amenities.*' => 'string',
        'location' => 'nullable|string',
        'booking_link' => 'nullable|url',
        'images.*' => 'nullable|image|max:2048',
        // 'images.*' => 'nullable|image|max:2048',
    'image_urls' => 'nullable|array',
    'image_urls.*' => 'nullable|url',
    ]);

    // تحديث بيانات الفندق
    $hotel->update($validated);

    // رفع الصور الجديدة إذا أُرسلت
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $imageFile) {
            $path = $imageFile->store('uploads/hotels', 'public');
            $hotel->images()->create([
                'url' => 'storage/' . $path,
            ]);
        }
    }
    if ($request->has('image_urls')) {
    foreach ($request->image_urls as $url) {
        $hotel->images()->create(['url' => $url]);
    }
}


    return response()->json($hotel->load('images'));
}


    /**
     * حذف فندق.
     */
    public function destroy($id)
    {
        Hotel::findOrFail($id)->delete();

        return response()->json(['message' => 'Hotel deleted successfully.']);
    }
}

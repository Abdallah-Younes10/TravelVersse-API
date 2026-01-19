<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ActivityController extends Controller
{

    public function uniqueTypes()
{
    $types = Activity::select('type')
        ->whereNotNull('type')
        ->distinct()
        ->pluck('type');

    return response()->json($types);
}

    public function index(Request $request)
{
    $locale = $request->header('Accept-Language') ?? app()->getLocale();

    $activities = Activity::with('images')->paginate(10);

    $data = $activities->getCollection()->map(function ($activity) use ($locale) {
        return [
            'id' => $activity->id,
            'name' => $locale === 'ar' ? $activity->name_ar : $activity->name_en,
            'description' => $locale === 'ar' ? $activity->description_ar : $activity->description_en,
            'location' => $activity->location,
            'price' => $activity->price,
            'rate' => $activity->rate,
            'duration' => $activity->duration,
            'type' => $activity->type,
            'booking_link' => $activity->booking_link,
            'images' => $activity->images->pluck('url'),
            'start_time' => $activity->start_time,
'live_guide' => $activity->live_guide,
'guide_languages' => $activity->guide_languages,

        ];
    });

    return response()->json([
        'current_page' => $activities->currentPage(),
        'last_page' => $activities->lastPage(),
        'per_page' => $activities->perPage(),
        'total' => $activities->total(),
        'data' => $data,
    ]);
}


   public function store(Request $request)
{
    $data = $request->validate([
        'name_en' => 'required|string|max:255',
        'name_ar' => 'required|string|max:255',
        'description_en' => 'nullable|string',
        'description_ar' => 'nullable|string',
        'location' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'booking_link' => 'nullable|url',
        'rate' => 'nullable|numeric|min:0|max:5',
        'duration' => 'nullable|string|max:255',
        'type' => 'nullable|string|max:255',
        'start_time' => 'nullable|array',
        'guide_languages' => 'nullable|array',
        'live_guide' => 'nullable|boolean',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'image_urls' => 'nullable|array',
        'image_urls.*' => 'nullable|url',
    ]);

    $activity = Activity::create($data);

    // رفع الصور من الجهاز
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('activities', 'public');
            $activity->images()->create(['url' => '/storage/' . $path]);
        }
    }

    // إدخال الصور كرابط
    if ($request->has('image_urls')) {
        foreach ($request->image_urls as $url) {
            $activity->images()->create(['url' => $url]);
        }
    }

    Cache::forget('activities_all_ar');
    Cache::forget('activities_all_en');

    return response()->json($activity, 201);
}


    public function show(Request $request, $id)
    {
        $locale = $request->header('Accept-Language') ?? app()->getLocale();
        $activity = Activity::with('images')->findOrFail($id);

        return response()->json([
    'id' => $activity->id,
    'name' => $locale == 'ar' ? $activity->name_ar : $activity->name_en,
    'description' => $locale == 'ar' ? $activity->description_ar : $activity->description_en,
    'location' => $activity->location,
    'price' => $activity->price,
    'rate' => $activity->rate,
    'duration' => $activity->duration,
    'type' => $activity->type,
    'booking_link' => $activity->booking_link,
    'images' => $activity->images->pluck('url'),
    'start_time' => $activity->start_time,
'live_guide' => $activity->live_guide,
'guide_languages' => $activity->guide_languages,

]);

    }

   public function update(Request $request, $id)
{
    $activity = Activity::findOrFail($id);

    $data = $request->validate([
        'name_en' => 'sometimes|required|string|max:255',
        'name_ar' => 'sometimes|required|string|max:255',
        'description_en' => 'nullable|string',
        'description_ar' => 'nullable|string',
        'location' => 'sometimes|required|string|max:255',
        'price' => 'sometimes|required|numeric|min:0',
        'booking_link' => 'nullable|url',
        'rate' => 'nullable|numeric|min:0|max:5',
        'duration' => 'nullable|string|max:255',
        'type' => 'nullable|string|max:255',
        'start_time' => 'nullable|array',
        'guide_languages' => 'nullable|array',
        'live_guide' => 'nullable|boolean',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'image_urls' => 'nullable|array',
        'image_urls.*' => 'nullable|url',
    ]);

    $activity->update($data);

    // صور جديدة مرفوعة
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('activities', 'public');
            $activity->images()->create(['url' => '/storage/' . $path]);
        }
    }

    // صور من رابط
    if ($request->has('image_urls')) {
        foreach ($request->image_urls as $url) {
            $activity->images()->create(['url' => $url]);
        }
    }

    Cache::forget('activities_all_ar');
    Cache::forget('activities_all_en');

    return response()->json($activity);
}



    public function destroy($id)
    {
        Activity::findOrFail($id)->delete();

        // حذف الكاش القديم بعد الحذف
        Cache::forget('activities_all_ar');
        Cache::forget('activities_all_en');

        return response()->json(['message' => 'Deleted']);
    }
}

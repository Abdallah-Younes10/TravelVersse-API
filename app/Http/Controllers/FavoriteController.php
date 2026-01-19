<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    
    public function index()
    {
        return Favorite::with('favoritable')->where('user_id', auth()->id())->get();
    }

    public function store(Request $request)
{
    $request->validate([
        'favoritable_id' => 'required|integer',
        'favoritable_type' => 'required|string',
    ]);

    $exists = Favorite::where('user_id', auth()->id())
        ->where('favoritable_id', $request->favoritable_id)
        ->where('favoritable_type', $request->favoritable_type)
        ->exists();

    if ($exists) {
        return response()->json(['message' => 'Already in favorites'], 409);
    }

    $favorite = Favorite::create([
        'user_id' => auth()->id(),
        'favoritable_id' => $request->favoritable_id,
        'favoritable_type' => $request->favoritable_type,
    ]);

    return response()->json($favorite->load('favoritable'), 201);
}


    public function destroy($id)
    {
        $favorite = Favorite::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $favorite->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

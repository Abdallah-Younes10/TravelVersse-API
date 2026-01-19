<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        return response()->json(Review::with('reviewable')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'rate' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'reviewable_type' => 'required|string',
            'reviewable_id' => 'required|integer',
        ]);

        $review = Review::create([
            'user_id' => Auth::id(),
            'rate' => $request->rate,
            'comment' => $request->comment,
            'reviewable_type' => $request->reviewable_type,
            'reviewable_id' => $request->reviewable_id,
        ]);

        return response()->json($review, 201);
    }

    public function show(Review $review)
    {
        return response()->json($review->load('reviewable'));
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->json(['message' => 'Review deleted successfully.']);
    }
}

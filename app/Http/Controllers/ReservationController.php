<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    // App\Http\Controllers\ReservationController.php

public function index()
{
    if (Auth::user()->usertype !== 'user') {
        return response()->json(Reservation::with('reservable')->get());
    }

    return response()->json(
        Reservation::where('user_id', Auth::id())
            ->with('reservable')
            ->get()
    );
}

   public function store(Request $request)
{
    $request->validate([
        'reservable_type' => 'required|string',
        'reservable_id' => 'required|integer',
    ]);

    // منع الحجز المكرر
    $existing = Reservation::where('user_id', Auth::id())
        ->where('reservable_type', $request->reservable_type)
        ->where('reservable_id', $request->reservable_id)
        ->first();

    if ($existing) {
        return response()->json(['message' => 'You already reserved this item.'], 400);
    }

    $reservation = Reservation::create([
        'user_id' => Auth::id(),
        'reservable_type' => $request->reservable_type,
        'reservable_id' => $request->reservable_id,
    ]);

    return response()->json($reservation, 201);
}


    public function show(Reservation $reservation)
    {
        if (Auth::id() !== $reservation->user_id && Auth::user()->usertype === 'user') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($reservation->load('reservable'));
    }

    public function destroy(Reservation $reservation)
    {
        if (Auth::id() !== $reservation->user_id && Auth::user()->usertype === 'user') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reservation->delete();
        return response()->json(['message' => 'Reservation deleted.']);
    }
    public function updateStatus(Request $request, $id)
{
    $reservation = Reservation::findOrFail($id);

    if (Auth::user()->usertype === 'user') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $request->validate([
        'status' => 'required|in:accepted,rejected',
    ]);

    $reservation->status = $request->status;
    $reservation->save();

    return response()->json(['message' => 'Status updated']);
}
public function pay($id)
{
    $reservation = Reservation::findOrFail($id);

    if (Auth::id() !== $reservation->user_id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    if ($reservation->status !== 'accepted') {
        return response()->json(['message' => 'Reservation not accepted yet'], 400);
    }

    $reservation->is_paid = true;
    $reservation->save();

    return response()->json(['message' => 'Fake payment completed']);
}

}

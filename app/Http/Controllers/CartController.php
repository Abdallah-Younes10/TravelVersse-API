<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return Cart::with('cartable')->where('user_id', auth()->id())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'cartable_id' => 'required|integer',
            'cartable_type' => 'required|string',
            'quantity' => 'nullable|integer|min:1'
        ]);

        return Cart::create([
            'user_id' => auth()->id(),
            'cartable_id' => $request->cartable_id,
            'cartable_type' => $request->cartable_type,
            'quantity' => $request->quantity ?? 1,
        ]);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $cart->update($request->only('quantity'));
        return $cart;
    }

    public function destroy($id)
    {
        Cart::where('id', $id)->where('user_id', auth()->id())->firstOrFail()->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;

class UserController extends Controller
{
    public function index()
{
    return response()->json(
    User::select('id', 'name', 'email', 'user_type')->paginate(10)
);

}
public function all()
{
    return response()->json(User::select('id', 'name', 'email', 'user_type')->get());
}

public function store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'role'     => 'required|string|max:50',
        'password' => 'required|string|min:6', // مبدئيًا نضع كلمة مرور
    ]);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'role'     => $request->role,
        'password' => bcrypt($request->password),
    ]);

    return response()->json($user, 201);
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        'role'  => 'required|string|max:50',
    ]);

    $user->update($request->only('name', 'email', 'role'));

    return response()->json($user);
}

public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return response()->json(['message' => 'User deleted']);
}
    public function profile(Request $request)
{
    $user = $request->user();
    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'image' => $user->image ? [
            'id' => $user->image->id,
            'url' => $user->image->url,
        ] : null,
    ]);
}


    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            // حذف الصورة القديمة (لو موجودة)
            if ($user->image) {
                if (str_contains($user->image->url, 'storage')) {
                    $relativePath = str_replace(asset('storage') . '/', '', $user->image->url);
                    Storage::disk('public')->delete($relativePath);
                }
                $user->image->delete();
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $url = asset('storage/' . $path);

            Image::create([
                'url' => $url,
                'imageable_id' => $user->id,
                'imageable_type' => get_class($user),
            ]);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->load('image')
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current' => 'required',
            'new'     => 'required|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }

        $user->password = bcrypt($request->new);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        // حذف صورة المستخدم من جدول images والتخزين
        if ($user->image) {
            if (str_contains($user->image->url, 'storage')) {
                $relativePath = str_replace(asset('storage') . '/', '', $user->image->url);
                Storage::disk('public')->delete($relativePath);
            }
            $user->image->delete();
        }

        $user->delete();

        return response()->json(['message' => 'Account deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use App\Models\Access;

class PhotoController extends Controller
{
    // Вывод фото по id
    public function Show($id, Request $request)
    {
        $token = $request->link;

        $photo = Photo::find($id);
        if (!$photo) {
            abort(404);
        }

        $access = Access::where('link', 'like', $token)->first();

        if (!$access) {
            abort(401, 'Error, User not found');
        }

        return response()->file(public_path('images/' . $photo->photo));
    }

    // Добавление фото
    public function AddPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg',
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $path = $request->file('photo')->store('images');

            $photo = new Photo();
            $photo->photo = $path;
            $photo->description = $request->input('description');

            $photo->save();

            return $photo;
        } else {
            return response()->json(['error' => 'Invalid or missing file.'], 400);
        }
    }
}

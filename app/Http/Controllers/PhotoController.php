<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use APP\Models\Photo;

class PhotoController extends Controller
{
    //Вывод фото по id
    public function Show($id)
    {
        $photo = Photo::find($id);

        if(!$photo)
        {
            abort(404);
        }

        return response()->file(public_path('images/'. $photo->filename));
    }

    public function AddPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes: jpeg, png, jpg|max: 2048',
            'description' => 'nullable|string'
        ]);

        $path = $request->file('photo')->AddPhoto('public/images');

        $photo = new Photo();
        $photo->photo = $path;
        $photo->description = $request->input('description'); 

        return "";
    }
}

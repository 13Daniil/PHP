<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use App\Models\User;

class PhotoController extends Controller
{
//Вывод фото по id
public function show($id, Request $request)
{
    $token = $request->link;

    
    $photo = Photo::find($id);
    if(!$photo)
    {
        abort(404);
    }
    
    $access = Access::where('link', 'like', $token)->first();

    if(!$access)
    {
        return "Error, User not found";
        abort(401);
    }
    
    return response()->file(public_path('images/'. $photo->filename));
}

public function addPhoto(Request $request)
{
    $request->validate([
        'photo' => 'required|image|mimes:jpeg,png,jpg',
        'description' => 'nullable|string'
    ]);

    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $path = $file->store('images'); // сохраняем файл на сервере в папке "images"

        $photo = new Photo();
        $photo->path = $path;
        $photo->description = $request->input('description');
        $photo->user_id = 1; //todo user
        $photo->folder_id = 1; //todo folder
        $photo->save();

        return response()->json(['photo' => $photo], 201);
    } else {
        return response()->json(['error' => 'Invalid or missing file.'], 400);
    }
}



}

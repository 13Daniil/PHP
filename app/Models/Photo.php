<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    // protected $table = 'photos';

    protected $fillable = ['photo', 'discription', 'user_id', 'folder_id'];

    public function user()
    {
        return $this->belongsTo(User::Class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::Class);
    }
}

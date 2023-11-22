<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    use HasFactory;

    protected $fillable = ['folder_id', 'photo_id', 'link'];

    public function folder()
    {
        return $this->belongsTo(Folder::Class);
    }

    public function photo()
    {
        return $this->belongsTo(Photo::Class);
    }
}

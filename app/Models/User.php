<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['login', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    public function photos()
    {
        return $this->hasMany(Photo::Class);
    }
}

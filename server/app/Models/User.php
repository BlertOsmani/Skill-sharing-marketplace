<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    public function courses(){
        return $this->hasMany(Course::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function favorites(){
        return $this->hasMany(Favorite::class);
    }
    public function favoriteAlbums(){
        return $this->hasMany(FavoriteAlbum::class);
    }
}

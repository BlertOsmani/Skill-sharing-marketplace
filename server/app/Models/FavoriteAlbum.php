<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteAlbum extends Model
{
    use HasFactory;
    protected $table = 'favorites_albums';
    public function favorites(){
        return $this->hasMany(Favorite::class, 'album_id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function level(){
        return $this->belongsTo(Level::class);
    }
    public function enrollments(){
        return $this->hasMany(Enrollment::class);
    }
}

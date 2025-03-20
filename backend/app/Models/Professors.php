<?php

namespace App\Models;

use App\Models\Courses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Professors extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade',
        'user_id'
    ];

    public function courses()
    {
        return $this->hasMany(Courses::class);
    }
}

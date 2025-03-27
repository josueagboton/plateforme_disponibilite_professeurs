<?php

namespace App\Models;

use App\Models\Courses;
use App\Models\Availability;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Professors extends User
{
    use HasFactory;

    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($professor) {
            $professor->grade = $professor->grade ?? 'Assistant';
        });
    }

    // public function courses()
    // {
    //     return $this->hasMany(Courses::class);
    // }

    public function availabilities()
    {
        return $this->hasMany(Availability::class, 'user_id');
    }
}

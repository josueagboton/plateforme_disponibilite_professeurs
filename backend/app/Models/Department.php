<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name'];

    public function levels()
    {
        return $this->belongsToMany(LevelEducation::class, 'department_level', 'department_id', 'level_education_id')
                    ->withTimestamps();
    }

    public function courses()
    {
        return $this->hasMany(Courses::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelEducation extends Model
{
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_level', 'level_education_id', 'department_id')
        ->withTimestamps();
    }

    public function courses()
    {
        return $this->hasMany(Courses::class);

    }
}

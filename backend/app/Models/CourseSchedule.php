<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model
{
   protected $fillable = ['event',
            'day',
            'hour_start' ,
            'hour_end',
            'user_id',
            'course_id',
            'department_id', 'level_education_id'
        ];

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id' );
    }

    public function professor()
    {
        return $this->belongsTo(Professors::class, 'user_id');
    }
}

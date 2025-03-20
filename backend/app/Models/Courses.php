<?php

namespace App\Models;

use App\Models\Professors;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Courses extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subject_taught',
        'duration',
        'description',
        'professor_id'
    ];

    public function professor()
    {
        return $this->belongsTo(Professors::class);
    }
}

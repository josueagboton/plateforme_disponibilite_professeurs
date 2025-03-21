<?php

namespace App\Models;

use App\Models\User;
use App\Models\Professors;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Availability extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'day',
        'hour_Start',
        'hour_End',
        'professor_id',
    ];

    public function professor()
    {
        return $this->belongsTo(Professors::class);
    }

    // Relation avec User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

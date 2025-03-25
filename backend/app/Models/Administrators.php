<?php

namespace App\Models;

use App\Enums\AdminFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Administrators extends User
{
    use HasFactory;

    protected $table = 'users';

}

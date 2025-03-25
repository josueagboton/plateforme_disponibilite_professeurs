<?php

namespace App\Models;

use App\Enums\AdminFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Administrators extends User
{
    use HasFactory;

    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($admin) {
            // Si aucune fonction n'est définie, définir par défaut 'Secretaire'
            $admin->function = $admin->function ?? AdminFunction::Secretaire->value;
        });
    }
}

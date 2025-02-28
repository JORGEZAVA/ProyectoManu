<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recetas extends Model
{
    use HasFactory;

    protected $table = 'recetas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen',
        "precio",
        "tipo",
    ];
}

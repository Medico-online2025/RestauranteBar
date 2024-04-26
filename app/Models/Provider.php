<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    protected $table = 'providers';
    protected $primaryKey = 'id';
    protected $fillable = 
    [
        'iddoc',
        'dni_ruc',
        'nombres',
        'direccion',
        'codigo_pais',
        'ubigeo',
        'correo'
    ];
}

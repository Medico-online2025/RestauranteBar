<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $table = 'clients';
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

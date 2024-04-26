<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SunatUser extends Model
{
    use HasFactory;
    protected $table        = 'sunat_users';
    protected $primaryKey   = 'id';

    protected $fillable     =
    [
        'nombre_comercial',
        'nombre_usuario',
        'clave_usuario',
        'clave_certificado',
        'ruc',
        'nombre_certificado'
    ];
}

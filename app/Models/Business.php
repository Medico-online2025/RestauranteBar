<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    protected $table        = 'businesses';
    protected $primaryKey   = 'id';

    protected $fillable     =
    [
        'ruc'.
        'razon_social',
        'direccion',
        'codigo_pais',
        'ubigeo',
        'telefono',
        'url_api',
        'email_accounting',
        'urbanizacion',
        'nombre_comercial',
        'usuario_sunat',
        'clave_sunat',
        'clave_certificado',
        'certificado',
        'servidor_sunat',
        'pago'
    ];
}

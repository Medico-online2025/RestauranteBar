<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $table        = 'services';
    protected $primaryKey   = 'id';

    protected $fillable =
    [
        'codigo_interno',
        'codigo_barras',
        'codigo_sunat',
        'descripcion',
        'marca',
        'modelo',
        'color',
        'idunidad',
        'idcodigo_igv',
        'igv',
        'precio_compra',
        'precio_venta',
        'impuesto'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBilling extends Model
{
    use HasFactory;
    protected $table        = 'detail_billings';
    protected $primaryKey   = 'id';
    protected $fillable     = 
    [
        'idfacturacion',
        'idproducto',
        'cantidad',
        'descuento',
        'igv',
        'id_afectacion_igv',
        'precio_unitario',
        'precio_total'
    ];
}

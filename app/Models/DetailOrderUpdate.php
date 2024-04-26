<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrderUpdate extends Model
{
    use HasFactory;
    protected $table    = 'detail_order_updates';
    protected $id       = 'id';
    protected $fillable = 
    [
        'idorden',
        'idproducto',
        'cantidad',
        'descuento',
        'igv',
        'id_afectacion_igv',
        'precio_unitario',
        'precio_total'
    ];
}

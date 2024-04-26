<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSaleNote extends Model
{
    use HasFactory;
    protected $table        = 'detail_sale_notes';
    protected $primaryKey   = 'id';
    protected $fillable     = 
    [
        'idnotaventa',
        'idproducto',
        'cantidad',
        'descuento',
        'igv',
        'id_afectacion_igv',
        'precio_unitario',
        'precio_total'
    ];
}
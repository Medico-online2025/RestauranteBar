<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;
    protected $table        = 'quotes';
    protected $primaryKey   = 'id';
    protected $fillable     = 
    [
        'idtipo_comprobante',
        'serie',
        'correlativo',
        'fecha_emision',
        'fecha_vencimiento',
        'hora',
        'idcliente',
        'idmoneda',
        'idpago',
        'modo_pago',
        'exonerada',
        'inafecta',
        'gravada',
        'anticipo',
        'igv',
        'gratuita',
        'otros_cargos',
        'total',
        'observaciones',
        'estado',
        'idusuario',
        'idcaja',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table        = 'orders';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'idtipo_documento',
        'idventa',
        'fecha',
        'hora',
        'exonerada',
        'inafecta',
        'gravada',
        'anticipo',
        'igv',
        'gratuita',
        'otros_cargos',
        'total',
        'observaciones',
        'idusuario',
        'estado',
        'idmesa',
        'ticket_comanda',
        'ticket_pre_cuenta'
    ];
}

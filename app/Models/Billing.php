<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;
    protected $table        = 'billings';
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
        'cdr',
        'anulado',
        'id_tipo_nota_credito',
        'idfactura_anular',
        'motivo',
        'estado_cpe',
        'errores',
        'nticket',
        'idusuario',
        'idcaja',
        'vuelto',
        'qr'
    ];
}

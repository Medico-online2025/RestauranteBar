<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchingCash extends Model
{
    use HasFactory;
    protected $table        = 'arching_cashes';
    protected $primaryKey   = 'id';
    protected $fillable     = 
    [
        'idcaja',
        'idusuario',
        'fecha_inicio',
        'fecha_fin',
        'monto_inicial',
        'monto_final',
        'total_ventas',
        'estado'
    ];
}

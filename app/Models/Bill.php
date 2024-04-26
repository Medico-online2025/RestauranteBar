<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $table        = 'bills';
    protected $primaryKey   = 'id'; 
    protected $fillable     =
    [
        'fecha_emision',
        'idpurchase_description',
        'cuenta',
        'monto',
        'detalle',
        'idusuario',
        'idcaja',
        'idarqueocaja'
    ];
}

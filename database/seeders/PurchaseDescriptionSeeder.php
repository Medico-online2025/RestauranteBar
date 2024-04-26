<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PurchaseDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $purchase_descriptions  =
        [
            [
                'descripcion'   => 'TRANSPORTE DE CARGA'
            ],
            [
                'descripcion'   => 'PASAJES TERRESTRES'
            ],
            [
                'descripcion'   => 'PASAJES AEREOS'
            ],
            [
                'descripcion'   => 'ALOJAMIENTO'
            ],
            [
                'descripcion'   => 'ALIMENTACION'
            ],
            [
                'descripcion'   => 'OTROS GASTOS DE VIAJE'
            ],
            [
                'descripcion'   => 'LEGAL Y TRIBUTARIA'
            ],
            [
                'descripcion'   => 'NOTARIA'
            ],
            [
                'descripcion'   => 'ASESORIA CONTABLE'
            ],
            [
                'descripcion'   => 'MERCADOTECNIA'
            ],
            [
                'descripcion'   => 'MEDIOAMBIENTAL'
            ],
            [
                'descripcion'   => 'INVESTIGACION Y DESARROLLO'
            ],
            [
                'descripcion'   => 'PRODUCCION'
            ],
            [
                'descripcion'   => 'PRODUCCION ENCARGADA A TERCEROS'
            ],
            [
                'descripcion'   => 'MANTENIMIENTO Y REPARACIONES'
            ],
            [
                'descripcion'   => 'INVERSION INMOBILIARIA'
            ],
            [
                'descripcion'   => 'ACTIVOS ADQUIRIDOS EN ARRENDAMIENTO FINANCIERO'
            ],
            [
                'descripcion'   => 'INTANGIBLES'
            ],
            [
                'descripcion'   => 'ACTIVOS BIOLOGICOS'
            ],
            [
                'descripcion'   => 'ALQUILERES EDIFICACIONES'
            ],
            [
                'descripcion'   => 'ALQUILERES MAQUINARIAS Y EQUIPOS DE EXPLOTACION'
            ],
            [
                'descripcion'   => 'ACTIVOS ADQUIRIDOS EN ARRENDAMIENTO FINANCIERO'
            ],
            [
                'descripcion'   => 'ALQUILERES EQUIPO DE TRANSPORTE'
            ],
            [
                'descripcion'   => 'ALQUILERES EQUIPOS DIVERSOS'
            ],
            [
                'descripcion'   => 'ENERGIA ELECTRICA'
            ],
            [
                'descripcion'   => 'GAS'
            ],
            [
                'descripcion'   => 'AGUA'
            ],
            [
                'descripcion'   => 'TELEFONO'
            ],
            [
                'descripcion'   => 'INTERNET'
            ],
            [
                'descripcion'   => 'RADIO'
            ],
            [
                'descripcion'   => 'CABLE'
            ],
            [
                'descripcion'   => 'PUBLICIDAD'
            ],
            [
                'descripcion'   => 'RELACIONES PUBLICAS'
            ],
            [
                'descripcion'   => 'SERVICIOS DE CONTRATISTAS'
            ],
            [
                'descripcion'   => 'GASTOS BANCARIOS'
            ],
            [
                'descripcion'   => 'GASTOS DE LABORATORIO'
            ],
            [
                'descripcion'   => 'OTROS SERVICIOS'
            ],
            [
                'descripcion'   => 'SERVICIO DE TRAMITES ANTE LA ADUANA'
            ],
            [
                'descripcion'   => 'SERVICIO DE ALMACENAMIENTO'
            ],
            [
                'descripcion'   => 'GASTOS POR TRIBUTOS'
            ],
            [
                'descripcion'   => 'IMPUESTO PREDIAL'
            ],
            [
                'descripcion'   => 'ARBITRIOS MUNICIPALES Y SEGURIDAD CIUDADANA'
            ],
            [
                'descripcion'   => 'IMPUESTO AL PATRIMONIO VEHICULAR'
            ],
            [
                'descripcion'   => 'LICENCIA DE FUNCIONAMIENTO'
            ],
            [
                'descripcion'   => 'SEGURO DE VIDA'
            ],
            [
                'descripcion'   => 'SEGURO SOAT'
            ],
            [
                'descripcion'   => 'REGALIAS'
            ],
            [
                'descripcion'   => 'SUSCRIPCIONES Y COTIZACIONES'
            ],
            [
                'descripcion'   => 'UTILES DE OFICINA'
            ],
            [
                'descripcion'   => 'COMBUSTIBLE'
            ],
            [
                'descripcion'   => 'SUMINISTROS MEDICAMENTOS'
            ],
            [
                'descripcion'   => 'IMPRESIONES'
            ],
            [
                'descripcion'   => 'DONACIONES'
            ],
            [
                'descripcion'   => 'SANCIONES ADMINISTRATIVAS'
            ],
            [
                'descripcion'   => 'MULTAS'
            ],
            [
                'descripcion'   => 'GASTOS VARIOS'
            ],
            [
                'descripcion'   => 'INTERESES Y GASTOS DE PAGARES'
            ],
            [
                'descripcion'   => 'IMPUESTO A LOS CREDITOS'
            ],
            [
                'descripcion'   => 'COMISIONES VARIAS'
            ],
            [
                'descripcion'   => 'TALONARIO DE CHEQUES'
            ],
            [
                'descripcion'   => 'COMISION DE MATENIMIENTO DE CUENTA'
            ],
        ];

        foreach ($purchase_descriptions as $purchase_description) 
        {
            foreach ($purchase_description as $k => $value) 
            {
                $new_purchase       = new \App\Models\PurchaseDescription();
                $new_purchase->{$k} = $value;
                $new_purchase->save();
            }
        }
    }
}

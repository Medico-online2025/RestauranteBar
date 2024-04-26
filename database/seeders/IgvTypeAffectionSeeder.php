<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IgvTypeAffectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $igv_type_affections  =
        [
            [
                'codigo'        => '10',
                'descripcion'   => 'Gravado - Operación Onerosa',
                'tipo'          => 'GRAV',
                'estado'        => 1
            ],

            [
                'codigo'        => '11',
                'descripcion'   => 'Gravado - Retiro por premio',
                'tipo'          => 'GRAV',
                'estado'        => 0
            ],

            [
                'codigo'        => '12',
                'descripcion'   => 'Gravado - Retiro por donación',
                'tipo'          => 'GRAV',
                'estado'        => 0
            ],

            [
                'codigo'        => '13',
                'descripcion'   => 'Gravado - Retiro',
                'tipo'          => 'GRAV',
                'estado'        => 0
            ],

            [
                'codigo'        => '14',
                'descripcion'   => 'Gravado - Retiro por publicidad',
                'tipo'          => 'GRAV',
                'estado'        => 0
            ],

            [
                'codigo'        => '15',
                'descripcion'   => 'Gravado - Bonificaciones',
                'tipo'          => 'GRAV',
                'estado'        => 0
            ],

            [
                'codigo'        => '16',
                'descripcion'   => 'Gravado - Retiro por entrega a trabajadores',
                'tipo'          => 'GRAV',
                'estado'        => 0
            ],

            [
                'codigo'        => '16',
                'descripcion'   => 'Gravado - Retiro por entrega a trabajadores',
                'tipo'          => 'GRAV',
                'estado'        => 0
            ],

            [
                'codigo'        => '17',
                'descripcion'   => 'Gravado - IVAP',
                'tipo'          => 'GRAV',
                'estado'        => 0
            ],

            [
                'codigo'        => '20',
                'descripcion'   => 'Exonerado - Operación Onerosa',
                'tipo'          => 'EXO',
                'estado'        => 1
            ],

            [
                'codigo'        => '21',
                'descripcion'   => 'Exonerado - Transferencia Gratuita',
                'tipo'          => 'EXO',
                'estado'        => 0
            ],

            [
                'codigo'        => '30',
                'descripcion'   => 'Inafecto - Operación Onerosa',
                'tipo'          => 'INA',
                'estado'        => 1
            ],

            [
                'codigo'        => '31',
                'descripcion'   => 'Inafecto - Retiro por Bonificación',
                'tipo'          => 'INA',
                'estado'        => 0
            ],

            [
                'codigo'        => '32',
                'descripcion'   => 'Inafecto - Retiro',
                'tipo'          => 'INA',
                'estado'        => 0
            ],

            [
                'codigo'        => '33',
                'descripcion'   => 'Inafecto - Retiro por Muestras Médica',
                'tipo'          => 'INA',
                'estado'        => 0
            ],

            [
                'codigo'        => '34',
                'descripcion'   => 'Inafecto -  Retiro por Convenio Colectivo',
                'tipo'          => 'INA',
                'estado'        => 0
            ],

            [
                'codigo'        => '35',
                'descripcion'   => 'Inafecto - Retiro por premio',
                'tipo'          => 'INA',
                'estado'        => 0
            ],

            [
                'codigo'        => '36',
                'descripcion'   => 'Inafecto -  Retiro por publicidad',
                'tipo'          => 'INA',
                'estado'        => 0
            ],

            [
                'codigo'        => '40',
                'descripcion'   => 'Exportación',
                'tipo'          => 'EXP',
                'estado'        => 0
            ],
        ];

        foreach($igv_type_affections as $igv_type_affection)
        {
            $new_igv_type_affection = new \App\Models\IgvTypeAffection();
            foreach($igv_type_affection as $key => $value)
            {
                $new_igv_type_affection->{$key}  = $value;
            }

            $new_igv_type_affection->save();
        }
    }
}

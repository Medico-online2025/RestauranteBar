<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CreditNoteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $credit_note_types  =
        [
            [
                'codigo'        => '01',
                'descripcion'   => 'ANULACIÓN DE LA OPERACIÓN',
                'estado'        => 1
            ],  

            [
                'codigo'        => '02',
                'descripcion'   => 'ANULACIÓN POR ERROR EN EL RUC',
                'estado'        => 1
            ],

            [
                'codigo'        => '03',
                'descripcion'   => 'CORRECCIÓN POR ERROR EN LA DESCRIPCIÓN',
                'estado'        => 1
            ],

            [
                'codigo'        => '04',
                'descripcion'   => 'DESCUENTO GLOBAL',
                'estado'        => 1
            ],

            [
                'codigo'        => '05',
                'descripcion'   => 'DESCUENTO POR ÍTEM',
                'estado'        => 1
            ],

            [
                'codigo'        => '06',
                'descripcion'   => 'DEVOLUCIÓN TOTAL',
                'estado'        => 1
            ],

            [
                'codigo'        => '07',
                'descripcion'   => 'DEVOLUCIÓN POR ÍTEM',
                'estado'        => 1
            ],

            [
                'codigo'        => '08',
                'descripcion'   => 'BONIFICACIÓN',
                'estado'        => 1
            ],

            [
                'codigo'        => '09',
                'descripcion'   => 'DISMINUCIÓN EN EL VALOR',
                'estado'        => 1
            ],

            [
                'codigo'        => '10',
                'descripcion'   => 'OTROS CONCEPTOS',
                'estado'        => 1
            ],

            [
                'codigo'        => '11',
                'descripcion'   => 'AJUSTES DE OPERACIONES DE EXPORTACIÓN',
                'estado'        => 1
            ],

            [
                'codigo'        => '12',
                'descripcion'   => 'AJUSTES AFECTOS AL IVAP',
                'estado'        => 1
            ],
        ];

        foreach($credit_note_types as $credit_note_type)
        {
            $new_credit_note_type = new \App\Models\CreditNoteType();
            foreach($credit_note_type as $key => $value)
            {
                $new_credit_note_type->{$key}  = $value;
            }

            $new_credit_note_type->save();
        }
    }
}

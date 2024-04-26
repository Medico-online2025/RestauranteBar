<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DebitNoteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $debit_note_types  =
        [
            [
                'codigo'        => '01',
                'descripcion'   => 'INTERESES POR MORA',
                'estado'        => 1
            ],  

            [
                'codigo'        => '02',
                'descripcion'   => 'AUMENTO EN EL VALOR',
                'estado'        => 1
            ],

            [
                'codigo'        => '03',
                'descripcion'   => 'PENALIDADES/ OTROS CONCEPTOS',
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

        foreach($debit_note_types as $debit_note_type)
        {
            $new_debit_note_type = new \App\Models\DebitNoteType();
            foreach($debit_note_type as $key => $value)
            {
                $new_debit_note_type->{$key}  = $value;
            }

            $new_debit_note_type->save();
        }
    }
}

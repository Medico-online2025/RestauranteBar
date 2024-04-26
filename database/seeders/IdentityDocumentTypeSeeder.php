<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IdentityDocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $identity_document_types  =
        [
            [
                'codigo'                    => '0',
                'descripcion'               => 'OTRO TIPO DE DOCUMENTO',
                'descripcion_documento'     => 'OTROS',
                'estado'                    => 1
            ],

            [
                'codigo'                    => '1',
                'descripcion'               => 'DOCUMENTO NACIONAL DE IDENTIDAD (DNI)',
                'descripcion_documento'     => 'DNI',
                'estado'                    => 1
            ],

            [
                'codigo'                    => '4',
                'descripcion'               => 'CARNET DE EXTRANJERIA',
                'descripcion_documento'     => 'CARNET DE EXTRANJERIA', 
                'estado'                    => 1
            ],

            [
                'codigo'                    => '6',
                'descripcion'               => 'REGISTRO ÚNICO DE CONTRIBUYENTES (RUC)',
                'descripcion_documento'     => 'RUC',
                'estado'                    => 1
            ],

            [
                'codigo'                    => '7',
                'descripcion'               => 'PASAPORTE',
                'descripcion_documento'     => 'PASAPORTE',
                'estado'                    => 1
            ],

            [
                'codigo'                    => 'A',
                'descripcion'               => 'CÉDULA DIPLOMÁTICA DE IDENTIDAD',
                'descripcion_documento'     => 'CÉDULA DIPLOMÁTICA DE IDENTIDAD',
                'estado'                    => 1
            ]
        ];

        foreach($identity_document_types as $identity_document_type)
        {
            $new_identity_document_type = new \App\Models\IdentityDocumentType();
            foreach($identity_document_type as $key => $value)
            {
                $new_identity_document_type->{$key}  = $value;
            }

            $new_identity_document_type->save();
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients =
        [
            [
                'iddoc'         => 4,
                'dni_ruc'       => '20610316884',
                'nombres'       => 'MYTEMS E.I.R.L.',
                'direccion'     => 'JR MANCO CAPAC 451',
                'codigo_pais'   => 'PE',
                'ubigeo'        => '220501',
                'telefono'      => ''
            ],

            [
                'iddoc'         => 2,
                'dni_ruc'       => '71433073',
                'nombres'       => 'KROWED NAJAR LOZANO',
                'direccion'     => 'JR MANCO CAPAC 451',
                'codigo_pais'   => 'PE',
                'ubigeo'        => '220501',
                'telefono'      => '950772205'
            ]
        ];

        foreach($clients as $client)
        {
            $new_client     = new \App\Models\Provider();
            foreach($client as $k => $value)
            {
                $new_client->{$k} = $value;
            }

            $new_client->save();
        }
    }
}

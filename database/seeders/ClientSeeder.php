<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
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
                'iddoc'         => 1,
                'dni_ruc'       => '00000000',
                'nombres'       => 'CLIENTES VARIOS',
                'direccion'     => '-',
                'codigo_pais'   => 'PE',
                'ubigeo'        => '220901',
                'telefono'      => ''
            ]
        ];

        foreach($clients as $client)
        {
            $new_client     = new \App\Models\Client();
            foreach($client as $k => $value)
            {
                $new_client->{$k} = $value;
            }

            $new_client->save();
        }
    }
}

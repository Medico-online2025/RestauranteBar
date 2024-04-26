<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BusineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $businesses =
        [
            [
                'ruc'               => '20610316884',
                'razon_social'      => 'MYTEMS E.I.R.L.',
                'direccion'         => 'JR MANCO CAPAC 452',
                'codigo_pais'       => 'PE',
                'ubigeo'            => '220501',
                'telefono'          => '', 
                'url_api'           => 'https://facturacion.mytems.cloud/', 
                'email_accounting'  => 'krowed17@gmail.com',
                'urbanizacion'      => '',
                'local'             => '',
                'nombre_comercial'  => 'MYTEMS E.I.R.L.',
                'usuario_sunat'     => 'MYTEMS23',
                'clave_sunat'       => 'Mytems23',
                'clave_certificado' => 'mytems2022',
                'certificado'       => '20610316884.pfx',
                'servidor_sunat'    => 3,
                'pago'              => 1
            ]
        ];

        foreach($businesses as $business)
        {
            $new_business = new \App\Models\Business();
            foreach($business as $k => $value)
            {
                $new_business->{$k} = $value;
            }

            $new_business->save();
        }
    }
}

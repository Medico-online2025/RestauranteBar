<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SunatUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sunat_users    =
        [
            [
                'nombre_comercial'      => 'DSIG',
                'nombre_usuario'        => 'admin',
                'clave_usuario'         => 'YWRtaW4=',
                'clave_certificado'     => 'YWRtaW4=',
                'ruc'                   => '20601733022',
                'nombre_certificado'    => '202020601733022.pfx'
            ]           
        ];

        foreach($sunat_users as $sunat_user)
        {
            $new_sunat_user = new \App\Models\SunatUser();
            foreach($sunat_user as $k => $value)
            {
                $new_sunat_user->{$k} = $value;
            }

            $new_sunat_user->save();
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles =
        [
            [
                'descripcion'   => 'SUPER ADMIN',
                'estado'        => 1
            ],

            [
                'descripcion'   => 'ADMINISTRADOR',
                'estado'        => 1
            ],

            [
                'descripcion'   => 'USUARIO',
                'estado'        => 1
            ],
        ];

        foreach($roles as $rol)
        {
            $new_rol = new \App\Models\Profile();
            foreach($rol as $key => $value)
            {
                $new_rol->{$key}  = $value;
            }

            $new_rol->save();
        }
    }
}

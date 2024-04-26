<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ArchingCashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arching_cashes =
        [
            [
                'idcaja'        => 1,
                'idusuario'     => 1,
                'fecha_inicio'  => date('Y-m-d'),
                'fecha_fin'     => date('Y-m-d'),
                'monto_inicial' => 100,
                'monto_final'   => 100,
                'total_ventas'  => 200,
                'estado'        => 1
            ],
        ];

        foreach($arching_cashes as $cash)
        {
            $new_cash     = new \App\Models\ArchingCash();
            foreach($cash as $k => $value)
            {
                $new_cash->{$k} = $value;
            }

            $new_cash->save();
        }
    }
}

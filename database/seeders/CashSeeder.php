<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cashes =
        [
            [
                'descripcion'         => 'CAJA 1'
            ]
        ];

        foreach($cashes as $cash)
        {
            $new_cash     = new \App\Models\Cash();
            foreach($cash as $k => $value)
            {
                $new_cash->{$k} = $value;
            }

            $new_cash->save();
        }
    }
}

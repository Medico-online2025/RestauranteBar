<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Monolog\Handler\FirePHPHandler;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies =
        [
            [
                'codigo'        => 'PEN',
                'descripcion'   => 'NUEVO SOL',
                'pais'          => 'PERÚ',
                'simbolo'       => 'S/',
                'estado'        => 1
            ],

            [
                'codigo'        => 'USD',
                'descripcion'   => 'US DOLLAR',
                'pais'          => 'ESTADOS UNIDOS (EEUU)',
                'simbolo'       => '$',
                'estado'        => 1
            ],
        ];

        foreach($currencies as $currency)
        {
            $new_currency = new \App\Models\Currency();
            foreach($currency as $k => $value)
            {
                $new_currency->{$k} = $value;
            }

            $new_currency->save();
        }
    }
}

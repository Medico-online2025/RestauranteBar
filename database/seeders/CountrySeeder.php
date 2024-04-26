<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries  =
        [
            [
                'codigo'        => 'PE',
                'descripcion'   => 'PERU',
                'estado'        => 1
            ]
        ];

        foreach($countries as $country)
        {
            $new_country = new \App\Models\Country();
            foreach($country as $key => $value)
            {
                $new_country->{$key}  = $value;
            }

            $new_country->save();
        }
    }
}

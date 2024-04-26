<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rooms =
        [
            [
                'descripcion'         => 'PRINCIPAL'
            ],
            [
                'descripcion'         => 'SEGUNDO PISO'
            ],
            [
                'descripcion'         => 'TERCER PISO'
            ]
        ];

        foreach($rooms as $room)
        {
            $new_room     = new \App\Models\Room();
            foreach($room as $k => $value)
            {
                $new_room->{$k} = $value;
            }

            $new_room->save();
        }
    }
}

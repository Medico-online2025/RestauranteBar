<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tables = 
        [
            [
                'descripcion'   => 'MESA 01',
                'estado'        => 1,
                'idsala'        => 1
            ],
            [
                'descripcion'   => 'MESA 02',
                'estado'        => 1,
                'idsala'        => 1
            ],
            [
                'descripcion'   => 'MESA 03',
                'estado'        => 1,
                'idsala'        => 1
            ],
            [
                'descripcion'   => 'MESA 04',
                'estado'        => 1,
                'idsala'        => 1
            ],
            [
                'descripcion'   => 'MESA 05',
                'estado'        => 1,
                'idsala'        => 1
            ],
            [
                'descripcion'   => 'MESA 06',
                'estado'        => 1,
                'idsala'        => 1
            ],
            [
                'descripcion'   => 'MESA 07',
                'estado'        => 1,
                'idsala'        => 1
            ],
            [
                'descripcion'   => 'MESA 08',
                'estado'        => 1,
                'idsala'        => 1
            ],
            [
                'descripcion'   => 'MESA 09',
                'estado'        => 1,
                'idsala'        => 1
            ],
            [
                'descripcion'   => 'MESA 10',
                'estado'        => 1,
                'idsala'        => 1
            ],
            [
                'descripcion'   => 'MESA 11',
                'estado'        => 1,
                'idsala'        => 1
            ],
            [
                'descripcion'   => 'MESA 12',
                'estado'        => 1,
                'idsala'        => 1
            ],
            [
                'descripcion'   => 'MESA 13',
                'estado'        => 1,
                'idsala'        => 2
            ],
            [
                'descripcion'   => 'MESA 14',
                'estado'        => 1,
                'idsala'        => 2
            ],
            [
                'descripcion'   => 'MESA 15',
                'estado'        => 1,
                'idsala'        => 2
            ],
            [
                'descripcion'   => 'MESA 16',
                'estado'        => 1,
                'idsala'        => 2
            ],
            [
                'descripcion'   => 'MESA 17',
                'estado'        => 1,
                'idsala'        => 3
            ],
            [
                'descripcion'   => 'MESA 18',
                'estado'        => 1,
                'idsala'        => 3
            ],
            [
                'descripcion'   => 'MESA 19',
                'estado'        => 1,
                'idsala'        => 3
            ],
            [
                'descripcion'   => 'MESA 20',
                'estado'        => 1,
                'idsala'        => 3
            ],
        ];
        foreach($tables as $table)
        {
            $new_table  = new \App\Models\Table();
            foreach($table as $k => $value)
            {
                $new_table->{$k} = $value;
            }
            $new_table->save();
        }
    }
}

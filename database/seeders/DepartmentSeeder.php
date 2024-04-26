<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = array(
            array('codigo' => '01','descripcion' => 'Amazonas'),
            array('codigo' => '02','descripcion' => 'Áncash'),
            array('codigo' => '03','descripcion' => 'Apurímac'),
            array('codigo' => '04','descripcion' => 'Arequipa'),
            array('codigo' => '05','descripcion' => 'Ayacucho'),
            array('codigo' => '06','descripcion' => 'Cajamarca'),
            array('codigo' => '07','descripcion' => 'Callao'),
            array('codigo' => '08','descripcion' => 'Cusco'),
            array('codigo' => '09','descripcion' => 'Huancavelica'),
            array('codigo' => '10','descripcion' => 'Huánuco'),
            array('codigo' => '11','descripcion' => 'Ica'),
            array('codigo' => '12','descripcion' => 'Junín'),
            array('codigo' => '13','descripcion' => 'La Libertad'),
            array('codigo' => '14','descripcion' => 'Lambayeque'),
            array('codigo' => '15','descripcion' => 'Lima'),
            array('codigo' => '16','descripcion' => 'Loreto'),
            array('codigo' => '17','descripcion' => 'Madre de Dios'),
            array('codigo' => '18','descripcion' => 'Moquegua'),
            array('codigo' => '19','descripcion' => 'Pasco'),
            array('codigo' => '20','descripcion' => 'Piura'),
            array('codigo' => '21','descripcion' => 'Puno'),
            array('codigo' => '22','descripcion' => 'San Martin'),
            array('codigo' => '23','descripcion' => 'Tacna'),
            array('codigo' => '24','descripcion' => 'Tumbes'),
            array('codigo' => '25','descripcion' => 'Ucayali')
        );

        foreach($departments as $department)
        {
            $new_department = new \App\Models\Department();
            foreach($department as $key => $value)
            {
                $new_department->{$key}  = $value;
            }

            $new_department->save();
        }
    }
}

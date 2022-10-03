<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PersonExternalTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('person_external_types')->delete();
        
        \DB::table('person_external_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Sin clasificar',
                'details' => NULL,
                'status' => 1,
                'created_at' => '2022-09-21 14:49:21',
                'updated_at' => '2022-09-21 14:49:21',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Proveedor',
                'details' => NULL,
                'status' => 1,
                'created_at' => '2022-09-21 14:49:31',
                'updated_at' => '2022-09-21 14:49:31',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Prestador de servicio',
                'details' => NULL,
                'status' => 1,
                'created_at' => '2022-09-21 14:49:40',
                'updated_at' => '2022-09-21 14:49:40',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Funcionario',
                'details' => NULL,
                'status' => 1,
                'created_at' => '2022-09-21 14:50:00',
                'updated_at' => '2022-09-21 14:50:00',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
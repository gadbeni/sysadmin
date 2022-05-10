<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IrremovabilityTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('irremovability_types')->delete();
        
        \DB::table('irremovability_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Discapacidad',
                'description' => NULL,
                'status' => 'activo',
                'created_at' => '2022-05-10 10:35:51',
                'updated_at' => '2022-05-10 10:35:51',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Maternidad/Paternidad',
                'description' => NULL,
                'status' => 'activo',
                'created_at' => '2022-05-10 10:36:10',
                'updated_at' => '2022-05-10 10:36:10',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Madre/Padre con discapacidad',
                'description' => NULL,
                'status' => 'activo',
                'created_at' => '2022-05-10 10:36:32',
                'updated_at' => '2022-05-10 10:36:32',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Tutora/Tutor con discapacidad',
                'description' => NULL,
                'status' => 'activo',
                'created_at' => '2022-05-10 10:36:50',
                'updated_at' => '2022-05-10 10:36:50',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProcedureTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('procedure_types')->delete();
        
        \DB::table('procedure_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Permanente',
                'planilla_id' => 1,
                'description' => NULL,
                'created_at' => '2022-01-17 16:13:01',
                'updated_at' => '2022-01-17 16:13:18',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Consultoría de línea',
                'planilla_id' => 3,
                'description' => NULL,
                'created_at' => '2022-01-17 16:13:36',
                'updated_at' => '2022-01-17 16:13:36',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Consultoría de producto',
                'planilla_id' => 4,
                'description' => NULL,
                'created_at' => '2022-01-17 16:13:45',
                'updated_at' => '2022-01-17 16:13:45',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Servicios',
                'planilla_id' => NULL,
                'description' => NULL,
                'created_at' => '2022-01-17 16:14:12',
                'updated_at' => '2022-01-17 16:14:12',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Personal eventual',
                'planilla_id' => 2,
                'description' => NULL,
                'created_at' => '2022-01-17 16:14:12',
                'updated_at' => '2022-01-17 16:14:12',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
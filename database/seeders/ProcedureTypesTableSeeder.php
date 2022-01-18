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
                'name' => 'Consutoría en línea',
                'description' => NULL,
                'created_at' => '2022-01-17 16:13:01',
                'updated_at' => '2022-01-17 16:13:18',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Consultoría de producto',
                'description' => NULL,
                'created_at' => '2022-01-17 16:13:36',
                'updated_at' => '2022-01-17 16:13:36',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Servicios',
                'description' => NULL,
                'created_at' => '2022-01-17 16:13:45',
                'updated_at' => '2022-01-17 16:13:45',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Personal eventual',
                'description' => NULL,
                'created_at' => '2022-01-17 16:14:12',
                'updated_at' => '2022-01-17 16:14:12',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
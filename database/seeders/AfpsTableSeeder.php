<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AfpsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('afps')->delete();
        
        \DB::table('afps')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Futuro',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-11-21 13:36:07',
                'updated_at' => '2022-11-21 13:36:07',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'BBVA Previsión',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-11-21 13:36:27',
                'updated_at' => '2022-11-21 13:36:27',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Gestora',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-11-21 13:37:04',
                'updated_at' => '2022-11-21 13:37:04',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfficesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('offices')->delete();
        
        \DB::table('offices')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Caja',
                'description' => NULL,
                'location' => NULL,
                'phone' => NULL,
                'created_at' => '2022-02-09 16:04:23',
                'updated_at' => '2022-02-09 16:04:23',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Previsión social',
                'description' => NULL,
                'location' => NULL,
                'phone' => NULL,
                'created_at' => '2022-02-09 16:04:32',
                'updated_at' => '2022-02-09 16:04:32',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cities')->delete();
        
        \DB::table('cities')->insert(array (
            0 => 
            array (
                'id' => 1,
                'states_id' => 1,
                'name' => 'Santísima Trinidad',
                'initials' => 'TDD',
                'location' => NULL,
                'created_at' => '2022-01-27 14:28:31',
                'updated_at' => '2022-01-27 14:28:31',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'states_id' => 1,
                'name' => 'Guayaramenrín',
                'initials' => 'GYA',
                'location' => NULL,
                'created_at' => '2022-01-27 14:28:47',
                'updated_at' => '2022-01-27 14:28:47',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'states_id' => 1,
                'name' => 'Riberalta',
                'initials' => 'RIB',
                'location' => NULL,
                'created_at' => '2022-01-27 14:28:59',
                'updated_at' => '2022-01-27 14:28:59',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'states_id' => 1,
                'name' => 'Santa Rosa',
                'initials' => NULL,
                'location' => NULL,
                'created_at' => '2022-01-27 14:33:39',
                'updated_at' => '2022-01-27 14:33:39',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'states_id' => 1,
                'name' => 'Reyes',
                'initials' => NULL,
                'location' => NULL,
                'created_at' => '2022-01-27 14:33:52',
                'updated_at' => '2022-01-27 14:33:52',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
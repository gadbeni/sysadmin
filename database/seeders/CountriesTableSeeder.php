<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('countries')->delete();
        
        \DB::table('countries')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Bolivia',
                'initials' => 'BOB',
                'created_at' => '2022-01-27 14:23:33',
                'updated_at' => '2022-01-27 14:23:33',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
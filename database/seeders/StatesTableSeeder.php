<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('states')->delete();
        
        \DB::table('states')->insert(array (
            0 => 
            array (
                'id' => 1,
                'country_id' => 1,
                'name' => 'Beni',
                'initials' => 'BN',
                'created_at' => '2022-01-27 14:23:46',
                'updated_at' => '2022-01-27 14:23:46',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'country_id' => 1,
                'name' => 'Santa Cruz',
                'initials' => 'SCZ',
                'created_at' => '2022-01-27 14:25:50',
                'updated_at' => '2022-01-27 14:25:50',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'country_id' => 1,
                'name' => 'Pando',
                'initials' => 'PND',
                'created_at' => '2022-01-27 14:26:10',
                'updated_at' => '2022-01-27 14:26:10',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'country_id' => 1,
                'name' => 'La Paz',
                'initials' => 'LP',
                'created_at' => '2022-01-27 14:26:27',
                'updated_at' => '2022-01-27 14:26:27',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'country_id' => 1,
                'name' => 'Chuquisaca',
                'initials' => 'CH',
                'created_at' => '2022-01-27 14:26:49',
                'updated_at' => '2022-01-27 14:26:49',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'country_id' => 1,
                'name' => 'Oruro',
                'initials' => 'OR',
                'created_at' => '2022-01-27 14:27:00',
                'updated_at' => '2022-01-27 14:27:00',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'country_id' => 1,
                'name' => 'Cochabamba',
                'initials' => 'COB',
                'created_at' => '2022-01-27 14:27:40',
                'updated_at' => '2022-01-27 14:27:40',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'country_id' => 1,
                'name' => 'Tarija',
                'initials' => 'TAR',
                'created_at' => '2022-01-27 14:27:53',
                'updated_at' => '2022-01-27 14:27:53',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'country_id' => 1,
                'name' => 'Potosí',
                'initials' => 'PO',
                'created_at' => '2022-01-27 14:28:08',
                'updated_at' => '2022-01-27 14:28:08',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
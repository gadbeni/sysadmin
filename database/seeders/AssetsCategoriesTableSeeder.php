<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AssetsCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('assets_categories')->delete();
        
        \DB::table('assets_categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Muebles',
                'description' => NULL,
                'created_at' => '2023-04-06 10:36:41',
                'updated_at' => '2023-04-06 10:36:41',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Equipos de computación',
                'description' => NULL,
                'created_at' => '2023-04-06 10:36:55',
                'updated_at' => '2023-04-06 10:36:55',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Electrodomésticos',
                'description' => NULL,
                'created_at' => '2023-04-06 10:37:26',
                'updated_at' => '2023-04-06 10:37:26',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Material de oficina',
                'description' => NULL,
                'created_at' => '2023-04-06 10:42:10',
                'updated_at' => '2023-04-06 10:42:10',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
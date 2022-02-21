<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SeniorityBonusTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('seniority_bonus_types')->delete();
        
        \DB::table('seniority_bonus_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => '2 a 4 años',
                'percentage' => 5,
                'status' => 1,
                'created_at' => '2022-02-17 13:25:07',
                'updated_at' => '2022-02-17 13:25:07',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'description' => '5 a 7 años',
                'percentage' => 11,
                'status' => 1,
                'created_at' => '2022-02-17 13:25:26',
                'updated_at' => '2022-02-17 13:25:26',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'description' => '8 a 10 años',
                'percentage' => 18,
                'status' => 1,
                'created_at' => '2022-02-17 13:25:46',
                'updated_at' => '2022-02-17 13:26:17',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'description' => '11 a 14 años',
                'percentage' => 26,
                'status' => 1,
                'created_at' => '2022-02-17 13:26:09',
                'updated_at' => '2022-02-17 13:26:09',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'description' => '15 a 19 años',
                'percentage' => 34,
                'status' => 1,
                'created_at' => '2022-02-17 13:26:47',
                'updated_at' => '2022-02-17 13:26:47',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'description' => '20 a 24 años',
                'percentage' => 42,
                'status' => 1,
                'created_at' => '2022-02-17 13:27:10',
                'updated_at' => '2022-02-17 13:27:10',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'description' => '25 o mas años',
                'percentage' => 50,
                'status' => 1,
                'created_at' => '2022-02-17 13:28:05',
                'updated_at' => '2022-02-17 13:28:05',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
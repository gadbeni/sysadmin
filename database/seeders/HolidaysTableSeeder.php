<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class HolidaysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('holidays')->delete();
        
        \DB::table('holidays')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Año nuevo',
                'description' => NULL,
                'day' => 1,
                'month' => 1,
                'year' => NULL,
                'status' => 1,
                'created_at' => '2023-11-24 13:53:33',
                'updated_at' => '2023-11-24 13:53:33',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Estado plurinacional',
                'description' => NULL,
                'day' => 22,
                'month' => 3,
                'year' => NULL,
                'status' => 1,
                'created_at' => '2023-11-24 13:54:00',
                'updated_at' => '2023-11-24 13:54:00',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Día del trabajador',
                'description' => NULL,
                'day' => 1,
                'month' => 5,
                'year' => NULL,
                'status' => 1,
                'created_at' => '2023-11-24 13:54:50',
                'updated_at' => '2023-11-24 13:55:03',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Año Nuevo aymara o andino',
                'description' => NULL,
                'day' => 21,
                'month' => 6,
                'year' => NULL,
                'status' => 1,
                'created_at' => '2023-11-24 13:55:38',
                'updated_at' => '2023-11-24 13:55:38',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Día de la Independencia',
                'description' => NULL,
                'day' => 6,
                'month' => 8,
                'year' => NULL,
                'status' => 1,
                'created_at' => '2023-11-24 13:56:04',
                'updated_at' => '2023-11-24 13:56:04',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Día de los Difuntos',
                'description' => NULL,
                'day' => 2,
                'month' => 11,
                'year' => NULL,
                'status' => 1,
                'created_at' => '2023-11-24 13:56:22',
                'updated_at' => '2023-11-24 13:56:22',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Navidad',
                'description' => NULL,
                'day' => 25,
                'month' => 12,
                'year' => NULL,
                'status' => 1,
                'created_at' => '2023-11-24 13:56:42',
                'updated_at' => '2023-11-24 13:56:42',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
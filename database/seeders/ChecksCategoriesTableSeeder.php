<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ChecksCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('checks_categories')->delete();
        
        \DB::table('checks_categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Caja chica',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-02-15 12:28:58',
                'updated_at' => '2022-02-15 12:32:15',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Pago de sueldo',
                'description' => 'pago de haberes mensuales de inversión, funcionamiento, estipendio, consultoría, etc.',
                'status' => 1,
                'created_at' => '2022-02-15 12:31:32',
                'updated_at' => '2022-02-15 12:31:32',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Fondo en avance',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-02-15 12:33:25',
                'updated_at' => '2022-02-15 12:33:25',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Proveedores',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-02-15 12:33:48',
                'updated_at' => '2022-02-15 12:33:48',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Lactancia',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-02-15 12:34:06',
                'updated_at' => '2022-02-15 12:34:06',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Judiciales',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-02-15 12:34:35',
                'updated_at' => '2022-02-15 12:34:35',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Vacaciones',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-02-15 12:35:20',
                'updated_at' => '2022-02-15 12:35:20',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Viaticos',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-02-15 12:35:37',
                'updated_at' => '2022-02-15 12:35:37',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Servicios Básicos',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-02-15 12:36:24',
                'updated_at' => '2022-02-15 12:36:24',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Prestación de servicios',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-02-15 12:37:02',
                'updated_at' => '2022-02-15 12:37:02',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
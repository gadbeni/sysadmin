<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MemosTypesGroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('memos_types_groups')->delete();
        
        \DB::table('memos_types_groups')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Asignación familiar',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Salarios gobernación',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Salarios corregimientos',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Salarios sub  gobernaciones',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Servicios básicos',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Transferencias',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Medios comunicación',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Proveedores',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Salario de consultoría',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Viáticos',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Reembolsos',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Fondos avance',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Fondo rotatorio',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Prediarios',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Refrigerio',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Alquiler',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Varios',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Servicios aéreos',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Devolución',
                'status' => 'activo',
                'created_at' => '2022-06-29 12:00:00',
                'updated_at' => '2022-06-29 12:00:00',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
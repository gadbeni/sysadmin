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
                'name' => 'Asignación Familiar',
                'status' => '1',
                'created_at' => '2022-08-10 14:11:52',
                'updated_at' => '2022-08-10 14:11:52',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Salarios Gobernación',
                'status' => '1',
                'created_at' => '2022-08-10 14:12:09',
                'updated_at' => '2022-08-10 14:12:09',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Salarios Corregimientos',
                'status' => '1',
                'created_at' => '2022-08-10 14:12:22',
                'updated_at' => '2022-08-10 14:12:22',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Salarios Sub  Gobernaciones',
                'status' => '1',
                'created_at' => '2022-08-10 14:12:30',
                'updated_at' => '2022-08-10 14:12:30',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Servicios Básicos',
                'status' => '1',
                'created_at' => '2022-08-10 14:12:40',
                'updated_at' => '2022-08-10 14:12:40',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Transferencias',
                'status' => '1',
                'created_at' => '2022-08-10 14:12:48',
                'updated_at' => '2022-08-10 14:12:48',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Medios Comunicación',
                'status' => '1',
                'created_at' => '2022-08-10 14:12:58',
                'updated_at' => '2022-08-10 14:12:58',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Proveedores',
                'status' => '1',
                'created_at' => '2022-08-10 14:13:09',
                'updated_at' => '2022-08-10 14:13:09',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Salario de Consultoría',
                'status' => '1',
                'created_at' => '2022-08-10 14:13:20',
                'updated_at' => '2022-08-10 14:13:20',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Viaticos',
                'status' => '1',
                'created_at' => '2022-08-10 14:13:28',
                'updated_at' => '2022-08-10 14:13:28',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Reembolsos',
                'status' => '1',
                'created_at' => '2022-08-10 14:13:36',
                'updated_at' => '2022-08-10 14:13:36',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Fondos Avance',
                'status' => '1',
                'created_at' => '2022-08-10 14:13:43',
                'updated_at' => '2022-08-10 14:13:43',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Fondo Rotatorio',
                'status' => '1',
                'created_at' => '2022-08-10 14:13:50',
                'updated_at' => '2022-08-10 14:13:50',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Prediarios',
                'status' => '1',
                'created_at' => '2022-08-10 14:13:58',
                'updated_at' => '2022-08-10 14:13:58',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Refrigerio',
                'status' => '1',
                'created_at' => '2022-08-10 14:14:06',
                'updated_at' => '2022-08-10 14:14:06',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Alquiler',
                'status' => '1',
                'created_at' => '2022-08-10 14:14:13',
                'updated_at' => '2022-08-10 14:14:13',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Varios',
                'status' => '1',
                'created_at' => '2022-08-10 14:14:21',
                'updated_at' => '2022-08-10 14:14:21',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Servicios Aereos',
                'status' => '1',
                'created_at' => '2022-08-10 14:14:28',
                'updated_at' => '2022-08-10 14:14:28',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Devolución',
                'status' => '1',
                'created_at' => '2022-08-10 14:14:38',
                'updated_at' => '2022-08-10 14:14:38',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
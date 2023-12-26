<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AssetMaintenanceTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('asset_maintenance_types')->delete();
        
        \DB::table('asset_maintenance_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'category' => 'Mantenimiento',
                'name' => 'MANTENIEMIENTO GRAL DE PC',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 11:59:53',
                'updated_at' => '2023-12-15 11:59:53',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'category' => 'Mantenimiento',
                'name' => 'DIAGNOSTICO DE LA PC',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:00:38',
                'updated_at' => '2023-12-15 12:00:38',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'category' => 'Instalación',
                'name' => 'INSTALCION  DE WINDOWS  Y PAQUETES',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:01:18',
                'updated_at' => '2023-12-15 12:01:18',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'category' => 'Instalación',
                'name' => 'REINSTALAR OFFICE',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:01:36',
                'updated_at' => '2023-12-15 12:01:36',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'category' => 'Instalación',
                'name' => 'ACTIVACION WINDOWS',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:01:57',
                'updated_at' => '2023-12-15 12:01:57',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'category' => 'Instalación',
                'name' => 'ACTIVACION DE OFFICE',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:02:08',
                'updated_at' => '2023-12-15 12:02:08',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'category' => 'Configuración',
                'name' => 'CARPETA COMPARTIDAS',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:02:24',
                'updated_at' => '2023-12-15 12:02:24',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'category' => 'Configuración',
                'name' => 'IMPRESORAS COMPARTIDAS',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:02:39',
                'updated_at' => '2023-12-15 12:02:39',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'category' => 'Configuración',
                'name' => 'IMPRESORAS EN RED',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:02:53',
                'updated_at' => '2023-12-15 12:02:53',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'category' => 'Mantenimiento',
                'name' => 'CORRECION DE ERRORES  DEL DISCO DURO',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:04:01',
                'updated_at' => '2023-12-15 12:04:01',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'category' => 'Otros',
                'name' => 'ASESORAMIENTO EN PAQUETES DE OFIMATICA',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:04:24',
                'updated_at' => '2023-12-15 12:04:24',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'category' => 'Limpieza',
                'name' => 'LIMPIEZA COMPLETA DE PC',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:04:41',
                'updated_at' => '2023-12-15 12:04:41',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'category' => 'Limpieza',
                'name' => 'CABEZALES DE IMPRESORAS',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:05:21',
                'updated_at' => '2023-12-15 12:05:21',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'category' => 'Configuración',
                'name' => 'ACCESO A INTERNET',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:05:36',
                'updated_at' => '2023-12-15 12:05:36',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'category' => 'Otros',
                'name' => 'BACKUPS DE INFORMACION',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:05:56',
                'updated_at' => '2023-12-15 12:05:56',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'category' => 'Limpieza',
                'name' => 'VIRUS EN MEMORIAS USB',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2023-12-15 12:07:45',
                'updated_at' => '2023-12-15 12:07:45',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DataTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('data_types')->delete();
        
        \DB::table('data_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'users',
                'slug' => 'users',
                'display_name_singular' => 'User',
                'display_name_plural' => 'Users',
                'icon' => 'voyager-person',
                'model_name' => 'TCG\\Voyager\\Models\\User',
                'policy_name' => 'TCG\\Voyager\\Policies\\UserPolicy',
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerUserController',
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"desc","default_search_key":null,"scope":null}',
                'created_at' => '2021-06-02 17:55:30',
                'updated_at' => '2021-11-17 15:18:19',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'menus',
                'slug' => 'menus',
                'display_name_singular' => 'Menu',
                'display_name_plural' => 'Menus',
                'icon' => 'voyager-list',
                'model_name' => 'TCG\\Voyager\\Models\\Menu',
                'policy_name' => NULL,
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2021-06-02 17:55:30',
                'updated_at' => '2021-06-02 17:55:30',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'roles',
                'slug' => 'roles',
                'display_name_singular' => 'Rol',
                'display_name_plural' => 'Roles',
                'icon' => 'voyager-lock',
                'model_name' => 'TCG\\Voyager\\Models\\Role',
                'policy_name' => NULL,
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController',
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"name","order_display_column":"name","order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2021-06-02 17:55:31',
                'updated_at' => '2021-09-23 09:28:28',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'permissions',
                'slug' => 'permissions',
                'display_name_singular' => 'Permiso',
                'display_name_plural' => 'Permisos',
                'icon' => 'voyager-list',
                'model_name' => 'App\\Models\\Permission',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"id","order_display_column":"id","order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2021-08-10 11:40:22',
                'updated_at' => '2021-09-16 15:32:44',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'cashiers',
                'slug' => 'cashiers',
                'display_name_singular' => 'Caja',
                'display_name_plural' => 'Cajas',
                'icon' => 'voyager-dollar',
                'model_name' => 'App\\Models\\Cashier',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2021-08-13 11:21:42',
                'updated_at' => '2021-08-13 13:25:55',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'social_security_types',
                'slug' => 'social-security-types',
                'display_name_singular' => 'Tipo de seguro social',
                'display_name_plural' => 'Tipos de seguro social',
                'icon' => 'voyager-credit-card',
                'model_name' => 'App\\Models\\SocialSecurityType',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2021-09-23 09:27:06',
                'updated_at' => '2021-09-23 09:52:33',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'checks_beneficiaries',
                'slug' => 'checks-beneficiaries',
                'display_name_singular' => 'Beneficiario',
                'display_name_plural' => 'Beneficiarios',
                'icon' => 'voyager-person',
                'model_name' => 'App\\Models\\ChecksBeneficiary',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2021-09-23 09:39:48',
                'updated_at' => '2021-09-23 09:47:06',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'dependences',
                'slug' => 'dependences',
                'display_name_singular' => 'Dependencia',
                'display_name_plural' => 'Dependencias',
                'icon' => 'voyager-company',
                'model_name' => 'App\\Models\\Dependence',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2021-10-14 10:46:30',
                'updated_at' => '2021-10-14 10:51:07',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'people',
                'slug' => 'people',
                'display_name_singular' => 'Persona',
                'display_name_plural' => 'Personas',
                'icon' => 'voyager-people',
                'model_name' => 'App\\Models\\Person',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"last_name","order_display_column":"first_name","order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2021-12-29 16:09:12',
                'updated_at' => '2022-01-21 10:40:45',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'programs',
                'slug' => 'programs',
                'display_name_singular' => 'Programa',
                'display_name_plural' => 'Programas',
                'icon' => 'voyager-documentation',
                'model_name' => 'App\\Models\\Program',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"name","order_display_column":"name","order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-01-04 10:56:30',
                'updated_at' => '2022-01-04 10:57:05',
            ),
            10 => 
            array (
                'id' => 12,
                'name' => 'procedure_types',
                'slug' => 'procedure-types',
                'display_name_singular' => 'Tipo de trámite',
                'display_name_plural' => 'Tipos de trámite',
                'icon' => 'voyager-documentation',
                'model_name' => 'App\\Models\\ProcedureType',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null}',
                'created_at' => '2022-01-17 16:09:20',
                'updated_at' => '2022-01-17 16:09:20',
            ),
        ));
        
        
    }
}
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
                'updated_at' => '2022-03-09 21:05:39',
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
                'model_name' => 'App\\Models\\Role',
                'policy_name' => NULL,
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController',
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"name","order_display_column":"name","order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2021-06-02 17:55:31',
                'updated_at' => '2022-02-25 12:53:46',
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
                'details' => '{"order_column":"table_name","order_display_column":"table_name","order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2021-08-10 11:40:22',
                'updated_at' => '2022-02-16 14:58:29',
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
                'server_side' => 1,
                'details' => '{"order_column":"last_name","order_display_column":"first_name","order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2021-12-29 16:09:12',
                'updated_at' => '2023-02-15 16:00:43',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'programs',
                'slug' => 'programs',
                'display_name_singular' => 'Programa/Proyecto',
                'display_name_plural' => 'Programas/Proyectos',
                'icon' => 'voyager-documentation',
                'model_name' => 'App\\Models\\Program',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"name","order_display_column":"name","order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-01-04 10:56:30',
                'updated_at' => '2024-01-11 14:04:43',
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
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-01-17 16:09:20',
                'updated_at' => '2022-02-03 16:03:59',
            ),
            11 => 
            array (
                'id' => 13,
                'name' => 'countries',
                'slug' => 'countries',
                'display_name_singular' => 'País',
                'display_name_plural' => 'Paises',
                'icon' => 'voyager-window-list',
                'model_name' => 'App\\Models\\Country',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-01-27 14:07:36',
                'updated_at' => '2022-01-27 14:22:35',
            ),
            12 => 
            array (
                'id' => 14,
                'name' => 'states',
                'slug' => 'states',
                'display_name_singular' => 'Departamento/Estado',
                'display_name_plural' => 'Departamentos/Estados',
                'icon' => 'voyager-window-list',
                'model_name' => 'App\\Models\\State',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-01-27 14:09:15',
                'updated_at' => '2022-01-27 14:30:30',
            ),
            13 => 
            array (
                'id' => 15,
                'name' => 'cities',
                'slug' => 'cities',
                'display_name_singular' => 'Ciudad',
                'display_name_plural' => 'Ciudades',
                'icon' => 'voyager-window-list',
                'model_name' => 'App\\Models\\City',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-01-27 14:14:05',
                'updated_at' => '2023-02-02 11:39:29',
            ),
            14 => 
            array (
                'id' => 16,
                'name' => 'offices',
                'slug' => 'offices',
                'display_name_singular' => 'Oficina',
                'display_name_plural' => 'Oficinas',
                'icon' => 'voyager-home',
                'model_name' => 'App\\Models\\Office',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null}',
                'created_at' => '2022-01-27 14:46:34',
                'updated_at' => '2022-01-27 14:46:34',
            ),
            15 => 
            array (
                'id' => 17,
                'name' => 'signatures',
                'slug' => 'signatures',
                'display_name_singular' => 'Firma autorizada',
                'display_name_plural' => 'Firmas autorizadas',
                'icon' => 'voyager-edit',
                'model_name' => 'App\\Models\\Signature',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-02-13 15:20:26',
                'updated_at' => '2023-06-27 11:33:08',
            ),
            16 => 
            array (
                'id' => 18,
                'name' => 'checks_categories',
                'slug' => 'checks-categories',
                'display_name_singular' => 'Tipo de Cheque',
                'display_name_plural' => 'Tipos de Cheques',
                'icon' => 'voyager-browser',
                'model_name' => 'App\\Models\\ChecksCategory',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null}',
                'created_at' => '2022-02-15 11:59:04',
                'updated_at' => '2022-02-15 11:59:04',
            ),
            17 => 
            array (
                'id' => 19,
                'name' => 'periods',
                'slug' => 'periods',
                'display_name_singular' => 'Periodo',
                'display_name_plural' => 'Periodos',
                'icon' => 'voyager-calendar',
                'model_name' => 'App\\Models\\Period',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"desc","default_search_key":null,"scope":null}',
                'created_at' => '2022-02-16 15:00:56',
                'updated_at' => '2024-01-29 11:11:05',
            ),
            18 => 
            array (
                'id' => 20,
                'name' => 'seniority_bonus_types',
                'slug' => 'seniority-bonus-types',
                'display_name_singular' => 'Bono antigüedad',
                'display_name_plural' => 'Bono antigüedad',
                'icon' => 'voyager-calendar',
                'model_name' => 'App\\Models\\SeniorityBonusType',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"id","order_display_column":"id","order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-02-17 13:21:28',
                'updated_at' => '2022-02-21 03:00:32',
            ),
            19 => 
            array (
                'id' => 21,
                'name' => 'seniority_bonus_people',
                'slug' => 'seniority-bonus-people',
                'display_name_singular' => 'Bono antigüedad',
                'display_name_plural' => 'Bonos antigüedad',
                'icon' => 'voyager-calendar',
                'model_name' => 'App\\Models\\SeniorityBonusPerson',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"id","order_display_column":"id","order_direction":"desc","default_search_key":null,"scope":null}',
                'created_at' => '2022-02-17 14:59:11',
                'updated_at' => '2022-03-09 05:16:52',
            ),
            20 => 
            array (
                'id' => 22,
                'name' => 'irremovability_types',
                'slug' => 'irremovability-types',
                'display_name_singular' => 'Tipo de inamovilidad',
                'display_name_plural' => 'Tipos de inamovilidad',
                'icon' => 'voyager-lock',
                'model_name' => 'App\\Models\\IrremovabilityType',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-05-10 10:29:11',
                'updated_at' => '2022-05-10 10:37:21',
            ),
            21 => 
            array (
                'id' => 23,
                'name' => 'person_external_types',
                'slug' => 'person-external-types',
                'display_name_singular' => 'Tipo de Beneficiario',
                'display_name_plural' => 'Tipos de Beneficiario',
                'icon' => 'voyager-list',
                'model_name' => 'App\\Models\\PersonExternalType',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-09-21 14:45:09',
                'updated_at' => '2023-02-15 16:27:18',
            ),
            22 => 
            array (
                'id' => 24,
                'name' => 'person_externals',
                'slug' => 'person-externals',
                'display_name_singular' => 'Beneficiario',
                'display_name_plural' => 'Beneficiarios',
                'icon' => 'voyager-people',
                'model_name' => 'App\\Models\\PersonExternal',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-09-21 14:59:27',
                'updated_at' => '2023-04-14 09:31:10',
            ),
            23 => 
            array (
                'id' => 25,
                'name' => 'memos_types_groups',
                'slug' => 'memos-types-groups',
                'display_name_singular' => 'Categoría de memo',
                'display_name_plural' => 'Categorías de memo',
                'icon' => 'voyager-certificate',
                'model_name' => 'App\\Models\\MemosTypesGroup',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"id","order_display_column":"id","order_direction":"desc","default_search_key":null}',
                'created_at' => '2022-09-29 15:02:21',
                'updated_at' => '2022-09-29 15:02:21',
            ),
            24 => 
            array (
                'id' => 26,
                'name' => 'memos_types',
                'slug' => 'memos-types',
                'display_name_singular' => 'Tipo de memo',
                'display_name_plural' => 'Tipos de memo',
                'icon' => 'voyager-window-list',
                'model_name' => 'App\\Models\\MemosType',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"id","order_display_column":"id","order_direction":"desc","default_search_key":null,"scope":null}',
                'created_at' => '2022-09-29 15:53:04',
                'updated_at' => '2023-02-28 11:32:58',
            ),
            25 => 
            array (
                'id' => 27,
                'name' => 'places',
                'slug' => 'places',
                'display_name_singular' => 'Lugar',
                'display_name_plural' => 'Lugares',
                'icon' => 'voyager-location',
                'model_name' => 'App\\Models\\Place',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-11-15 00:12:22',
                'updated_at' => '2022-11-15 15:59:24',
            ),
            26 => 
            array (
                'id' => 28,
                'name' => 'cultures',
                'slug' => 'cultures',
                'display_name_singular' => 'Cultura',
                'display_name_plural' => 'Culturas',
                'icon' => 'voyager-file-text',
                'model_name' => 'App\\Models\\Culture',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-11-15 13:28:57',
                'updated_at' => '2022-11-15 13:30:56',
            ),
            27 => 
            array (
                'id' => 29,
                'name' => 'posts',
                'slug' => 'posts',
                'display_name_singular' => 'Noticia',
                'display_name_plural' => 'Noticias',
                'icon' => 'voyager-news',
                'model_name' => 'App\\Models\\Post',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null}',
                'created_at' => '2022-11-15 16:00:15',
                'updated_at' => '2022-11-15 16:00:15',
            ),
            28 => 
            array (
                'id' => 30,
                'name' => 'afps',
                'slug' => 'afps',
                'display_name_singular' => 'Tipo de AFP',
                'display_name_plural' => 'Tipos de AFP',
                'icon' => 'voyager-list',
                'model_name' => 'App\\Models\\Afp',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null}',
                'created_at' => '2022-11-21 13:26:08',
                'updated_at' => '2022-11-21 13:26:08',
            ),
            29 => 
            array (
                'id' => 36,
                'name' => 'contracts_alternates_jobs',
                'slug' => 'contracts-alternates-jobs',
                'display_name_singular' => 'Cargo Alterno',
                'display_name_plural' => 'Cargos Alternos',
                'icon' => 'voyager-list',
                'model_name' => 'App\\Models\\ContractsAlternatesJob',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-12-10 10:34:47',
                'updated_at' => '2023-02-13 11:57:11',
            ),
            30 => 
            array (
                'id' => 37,
                'name' => 'unidades',
                'slug' => 'unidades',
                'display_name_singular' => 'Unidad Adm.',
                'display_name_plural' => 'Unidades Adm.',
                'icon' => 'voyager-list',
                'model_name' => 'App\\Models\\Unidad',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":"activo"}',
                'created_at' => '2022-12-15 16:30:00',
                'updated_at' => '2024-02-02 15:27:01',
            ),
            31 => 
            array (
                'id' => 40,
                'name' => 'direcciones',
                'slug' => 'direcciones',
                'display_name_singular' => 'Dirección',
                'display_name_plural' => 'Direcciones',
                'icon' => 'voyager-list',
                'model_name' => 'App\\Models\\Direccion',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":"activo"}',
                'created_at' => '2023-02-02 10:42:32',
                'updated_at' => '2024-02-02 15:22:33',
            ),
            32 => 
            array (
                'id' => 41,
                'name' => 'suggestions',
                'slug' => 'suggestions',
                'display_name_singular' => 'Sugerencia',
                'display_name_plural' => 'Sugerencias',
                'icon' => 'voyager-logbook',
                'model_name' => 'App\\Models\\Suggestion',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2023-03-27 14:27:20',
                'updated_at' => '2023-03-29 16:02:47',
            ),
            33 => 
            array (
                'id' => 42,
                'name' => 'jobs',
                'slug' => 'jobs',
                'display_name_singular' => 'Cargo',
                'display_name_plural' => 'Cargos',
                'icon' => 'voyager-list',
                'model_name' => 'App\\Models\\Job',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 1,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2023-03-29 16:13:49',
                'updated_at' => '2024-02-08 09:54:49',
            ),
            34 => 
            array (
                'id' => 43,
                'name' => 'assets_categories',
                'slug' => 'assets-categories',
                'display_name_singular' => 'Sección',
                'display_name_plural' => 'Secciones',
                'icon' => 'voyager-list',
                'model_name' => 'App\\Models\\AssetsCategory',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2023-04-06 09:38:33',
                'updated_at' => '2023-09-05 08:15:15',
            ),
            35 => 
            array (
                'id' => 44,
                'name' => 'assets_subcategories',
                'slug' => 'assets-subcategories',
                'display_name_singular' => 'Grupo',
                'display_name_plural' => 'Grupos',
                'icon' => 'voyager-list',
                'model_name' => 'App\\Models\\AssetsSubcategory',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2023-04-06 09:39:54',
                'updated_at' => '2023-09-05 08:15:30',
            ),
            36 => 
            array (
                'id' => 46,
                'name' => 'schedules',
                'slug' => 'schedules',
                'display_name_singular' => 'Horario',
                'display_name_plural' => 'Horarios',
                'icon' => 'voyager-calendar',
                'model_name' => 'App\\Models\\Schedule',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2023-08-01 18:04:25',
                'updated_at' => '2024-02-14 16:04:16',
            ),
            37 => 
            array (
                'id' => 48,
                'name' => 'holidays',
                'slug' => 'holidays',
                'display_name_singular' => 'Feriado',
                'display_name_plural' => 'Feriados',
                'icon' => 'voyager-calendar',
                'model_name' => 'App\\Models\\Holiday',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"id","order_display_column":"id","order_direction":"asc","default_search_key":"name","scope":null}',
                'created_at' => '2023-11-24 13:32:27',
                'updated_at' => '2023-11-24 13:58:33',
            ),
            38 => 
            array (
                'id' => 50,
                'name' => 'asset_maintenance_types',
                'slug' => 'asset-maintenance-types',
                'display_name_singular' => 'Trabajo recurrente',
                'display_name_plural' => 'Trabajos recurrentes',
                'icon' => 'voyager-list',
                'model_name' => 'App\\Models\\assetMaintenanceType',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2023-12-15 11:39:20',
                'updated_at' => '2023-12-15 11:59:15',
            ),
            39 => 
            array (
                'id' => 51,
                'name' => 'attendance_permit_types',
                'slug' => 'attendance-permit-types',
                'display_name_singular' => 'Tipo de permiso',
                'display_name_plural' => 'Tipo de permisos',
                'icon' => 'voyager-list',
                'model_name' => 'App\\Models\\AttendancePermitType',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2024-03-12 14:59:22',
                'updated_at' => '2024-03-13 14:05:07',
            ),
        ));
        
        
    }
}
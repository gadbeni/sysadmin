<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_items')->delete();
        
        \DB::table('menu_items')->insert(array (
            0 => 
            array (
                'id' => 1,
                'menu_id' => 1,
                'title' => 'Inicio',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-home',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 1,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2021-08-10 11:41:08',
                'route' => 'voyager.dashboard',
                'parameters' => 'null',
            ),
            1 => 
            array (
                'id' => 2,
                'menu_id' => 1,
                'title' => 'Media',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-images',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 3,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2021-08-17 14:46:33',
                'route' => 'voyager.media.index',
                'parameters' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'menu_id' => 1,
                'title' => 'Usuarios',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-person',
                'color' => '#000000',
                'parent_id' => 11,
                'order' => 1,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2021-08-10 11:37:17',
                'route' => 'voyager.users.index',
                'parameters' => 'null',
            ),
            3 => 
            array (
                'id' => 4,
                'menu_id' => 1,
                'title' => 'Roles',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => NULL,
                'parent_id' => 11,
                'order' => 2,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2021-06-02 14:08:05',
                'route' => 'voyager.roles.index',
                'parameters' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'menu_id' => 1,
                'title' => 'Herramientas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-tools',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 10,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2021-11-16 10:28:43',
                'route' => NULL,
                'parameters' => '',
            ),
            5 => 
            array (
                'id' => 6,
                'menu_id' => 1,
                'title' => 'Menu Builder',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-list',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 1,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-06-02 14:07:22',
                'route' => 'voyager.menus.index',
                'parameters' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'menu_id' => 1,
                'title' => 'Database',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-data',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 2,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-08-13 11:21:57',
                'route' => 'voyager.database.index',
                'parameters' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'menu_id' => 1,
                'title' => 'Compass',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-compass',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 4,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-08-17 14:46:33',
                'route' => 'voyager.compass.index',
                'parameters' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'menu_id' => 1,
                'title' => 'BREAD',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-bread',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 5,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-08-17 14:46:33',
                'route' => 'voyager.bread.index',
                'parameters' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'menu_id' => 1,
                'title' => 'Configuración',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-settings',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 9,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-11-16 10:28:43',
                'route' => 'voyager.settings.index',
                'parameters' => 'null',
            ),
            10 => 
            array (
                'id' => 11,
                'menu_id' => 1,
                'title' => 'Seguridad',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 8,
                'created_at' => '2021-06-02 14:07:53',
                'updated_at' => '2021-10-14 10:46:59',
                'route' => NULL,
                'parameters' => '',
            ),
            11 => 
            array (
                'id' => 12,
                'menu_id' => 1,
                'title' => 'Limpiar cache',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-refresh',
                'color' => '#000000',
                'parent_id' => 5,
                'order' => 6,
                'created_at' => '2021-06-25 18:03:59',
                'updated_at' => '2021-08-17 14:46:33',
                'route' => 'clear.cache',
                'parameters' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'menu_id' => 1,
                'title' => 'Permisos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-list',
                'color' => NULL,
                'parent_id' => 11,
                'order' => 3,
                'created_at' => '2021-08-10 11:40:22',
                'updated_at' => '2021-08-10 11:40:35',
                'route' => 'voyager.permissions.index',
                'parameters' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'menu_id' => 1,
                'title' => 'Planillas y cajas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-dollar',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 3,
                'created_at' => '2021-08-13 10:25:22',
                'updated_at' => '2021-08-17 14:46:36',
                'route' => NULL,
                'parameters' => '',
            ),
            14 => 
            array (
                'id' => 15,
                'menu_id' => 1,
                'title' => 'Planillas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-file-text',
                'color' => '#000000',
                'parent_id' => 14,
                'order' => 2,
                'created_at' => '2021-08-13 10:27:20',
                'updated_at' => '2021-08-13 11:22:00',
                'route' => 'planillas.index',
                'parameters' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'menu_id' => 1,
                'title' => 'Cajas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-dollar',
                'color' => NULL,
                'parent_id' => 14,
                'order' => 1,
                'created_at' => '2021-08-13 11:21:42',
                'updated_at' => '2021-08-13 11:22:00',
                'route' => 'voyager.cashiers.index',
                'parameters' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'menu_id' => 1,
                'title' => 'Bóveda',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-treasure',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 2,
                'created_at' => '2021-08-17 14:46:10',
                'updated_at' => '2021-08-17 14:46:36',
                'route' => 'vaults.index',
                'parameters' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'menu_id' => 1,
                'title' => 'Reportes',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-bar-chart',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 7,
                'created_at' => '2021-09-02 10:31:27',
                'updated_at' => '2021-09-23 09:41:55',
                'route' => NULL,
                'parameters' => '',
            ),
            18 => 
            array (
                'id' => 19,
                'menu_id' => 1,
                'title' => 'Contraloría',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-people',
                'color' => '#000000',
                'parent_id' => 18,
                'order' => 1,
                'created_at' => '2021-09-02 10:32:08',
                'updated_at' => '2021-09-20 10:49:18',
                'route' => 'reports.humans_resources.contraloria',
                'parameters' => 'null',
            ),
            19 => 
            array (
                'id' => 20,
                'menu_id' => 1,
                'title' => 'Aniversarios',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-calendar',
                'color' => '#000000',
                'parent_id' => 18,
                'order' => 2,
                'created_at' => '2021-09-03 10:55:06',
                'updated_at' => '2021-09-03 10:56:05',
                'route' => 'reports.humans_resources.aniversarios',
                'parameters' => 'null',
            ),
            20 => 
            array (
                'id' => 21,
                'menu_id' => 1,
                'title' => 'Previsión social',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-people',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 4,
                'created_at' => '2021-09-16 15:36:15',
                'updated_at' => '2021-09-16 15:37:19',
                'route' => NULL,
                'parameters' => '',
            ),
            21 => 
            array (
                'id' => 22,
                'menu_id' => 1,
                'title' => 'Pagos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-dollar',
                'color' => '#000000',
                'parent_id' => 21,
                'order' => 2,
                'created_at' => '2021-09-16 15:37:10',
                'updated_at' => '2021-09-20 14:40:42',
                'route' => 'payments.index',
                'parameters' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'menu_id' => 1,
                'title' => 'Pagos al seguro social',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-dollar',
                'color' => '#000000',
                'parent_id' => 18,
                'order' => 3,
                'created_at' => '2021-09-20 08:27:42',
                'updated_at' => '2021-09-23 09:43:02',
                'route' => 'reports.social_security.payments',
                'parameters' => 'null',
            ),
            23 => 
            array (
                'id' => 24,
                'menu_id' => 1,
                'title' => 'Cheques',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-window-list',
                'color' => '#000000',
                'parent_id' => 21,
                'order' => 1,
                'created_at' => '2021-09-20 14:40:07',
                'updated_at' => '2021-09-20 14:40:42',
                'route' => 'checks.index',
                'parameters' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'menu_id' => 1,
                'title' => 'Previsión social',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-people',
                'color' => '#000000',
                'parent_id' => 28,
                'order' => 1,
                'created_at' => '2021-09-22 10:08:09',
                'updated_at' => '2021-10-18 09:59:05',
                'route' => 'social.security.print.index',
                'parameters' => 'null',
            ),
            25 => 
            array (
                'id' => 26,
                'menu_id' => 1,
                'title' => 'Tipos de seguro social',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-credit-card',
                'color' => NULL,
                'parent_id' => 29,
                'order' => 1,
                'created_at' => '2021-09-23 09:27:06',
                'updated_at' => '2021-09-23 09:42:00',
                'route' => 'voyager.social-security-types.index',
                'parameters' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'menu_id' => 1,
                'title' => 'Beneficiarios',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-person',
                'color' => NULL,
                'parent_id' => 29,
                'order' => 2,
                'created_at' => '2021-09-23 09:39:48',
                'updated_at' => '2021-09-23 09:42:02',
                'route' => 'voyager.checks-beneficiaries.index',
                'parameters' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'menu_id' => 1,
                'title' => 'Impresiones',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'glyphicon glyphicon-print',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 6,
                'created_at' => '2021-09-23 09:41:00',
                'updated_at' => '2021-09-23 09:41:55',
                'route' => NULL,
                'parameters' => '',
            ),
            28 => 
            array (
                'id' => 29,
                'menu_id' => 1,
                'title' => 'Parámetros',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-params',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 5,
                'created_at' => '2021-09-23 09:41:44',
                'updated_at' => '2021-09-23 09:41:55',
                'route' => NULL,
                'parameters' => '',
            ),
            29 => 
            array (
                'id' => 30,
                'menu_id' => 1,
                'title' => 'Dependencias',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-company',
                'color' => NULL,
                'parent_id' => 29,
                'order' => 3,
                'created_at' => '2021-10-14 10:46:30',
                'updated_at' => '2021-10-14 10:46:59',
                'route' => 'voyager.dependences.index',
                'parameters' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'menu_id' => 1,
                'title' => 'Pagos anuales',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-dollar',
                'color' => '#000000',
                'parent_id' => 18,
                'order' => 4,
                'created_at' => '2021-11-16 10:28:33',
                'updated_at' => '2021-11-16 10:28:43',
                'route' => 'generate.payments.index',
                'parameters' => NULL,
            ),
        ));
        
        
    }
}
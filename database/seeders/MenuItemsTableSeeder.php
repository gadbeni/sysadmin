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
                'order' => 11,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2022-01-27 14:18:26',
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
                'order' => 10,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2022-01-27 14:18:26',
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
                'order' => 9,
                'created_at' => '2021-06-02 14:07:53',
                'updated_at' => '2022-01-27 14:18:26',
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
                'order' => 8,
                'created_at' => '2021-09-02 10:31:27',
                'updated_at' => '2021-12-30 09:24:09',
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
                'updated_at' => '2022-01-17 00:30:23',
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
                'updated_at' => '2022-01-17 00:30:23',
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
                'updated_at' => '2022-01-17 00:30:23',
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
                'order' => 7,
                'created_at' => '2021-09-23 09:41:00',
                'updated_at' => '2021-12-30 09:24:16',
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
                'order' => 6,
                'created_at' => '2021-09-23 09:41:44',
                'updated_at' => '2021-12-30 09:24:15',
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
                'title' => 'Contrataciones',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-certificate',
                'color' => '#000000',
                'parent_id' => 18,
                'order' => 7,
                'created_at' => '2021-11-16 23:48:42',
                'updated_at' => '2022-01-20 15:25:19',
                'route' => 'reports.social_security.contracts',
                'parameters' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'menu_id' => 1,
                'title' => 'Planillas manuales',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-edit',
                'color' => '#000000',
                'parent_id' => 21,
                'order' => 3,
                'created_at' => '2021-11-16 14:36:16',
                'updated_at' => '2022-01-06 16:41:49',
                'route' => 'spreadsheets.index',
                'parameters' => 'null',
            ),
            32 => 
            array (
                'id' => 33,
                'menu_id' => 1,
                'title' => 'Pagos anuales',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-dollar',
                'color' => '#000000',
                'parent_id' => 18,
                'order' => 10,
                'created_at' => '2021-11-23 21:53:04',
                'updated_at' => '2022-01-20 15:25:19',
                'route' => 'social-security.payments.group.index',
                'parameters' => 'null',
            ),
            33 => 
            array (
                'id' => 34,
                'menu_id' => 1,
                'title' => 'Planillas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-calendar',
                'color' => '#000000',
                'parent_id' => 18,
                'order' => 9,
                'created_at' => '2021-11-23 21:59:05',
                'updated_at' => '2022-01-20 15:25:19',
                'route' => 'reports.social_security.spreadsheets',
                'parameters' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'menu_id' => 1,
                'title' => 'Cajas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-dollar',
                'color' => '#000000',
                'parent_id' => 18,
                'order' => 11,
                'created_at' => '2021-11-30 09:42:43',
                'updated_at' => '2022-01-20 15:25:19',
                'route' => 'reports.cashier.cashiers.index',
                'parameters' => 'null',
            ),
            35 => 
            array (
                'id' => 36,
                'menu_id' => 1,
                'title' => 'Pagos realizados',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-certificate',
                'color' => '#000000',
                'parent_id' => 18,
                'order' => 12,
                'created_at' => '2021-11-30 13:22:57',
                'updated_at' => '2022-01-20 15:25:19',
                'route' => 'reports.cashier.payments.index',
                'parameters' => NULL,
            ),
            36 => 
            array (
                'id' => 38,
                'menu_id' => 1,
                'title' => 'Cierres de bóveda',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-bar-chart',
                'color' => '#000000',
                'parent_id' => 18,
                'order' => 13,
                'created_at' => '2021-12-08 13:42:30',
                'updated_at' => '2022-01-20 15:25:19',
                'route' => 'reports.cashier.vaults.index',
                'parameters' => 'null',
            ),
            37 => 
            array (
                'id' => 39,
                'menu_id' => 1,
                'title' => 'Tickets de caja',
                'url' => '',
                'target' => '_blank',
                'icon_class' => 'voyager-tv',
                'color' => '#000000',
                'parent_id' => 14,
                'order' => 4,
                'created_at' => '2021-12-20 12:07:31',
                'updated_at' => '2022-01-17 00:30:34',
                'route' => 'cashiers.tickets',
                'parameters' => 'null',
            ),
            38 => 
            array (
                'id' => 40,
                'menu_id' => 1,
                'title' => 'Aportes individuales',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-person',
                'color' => '#000000',
                'parent_id' => 18,
                'order' => 6,
                'created_at' => '2021-12-22 19:42:11',
                'updated_at' => '2022-01-20 15:25:19',
                'route' => 'reports.social_security.personal.payments.index',
                'parameters' => NULL,
            ),
            39 => 
            array (
                'id' => 41,
                'menu_id' => 1,
                'title' => 'Carátula',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-news',
                'color' => '#000000',
                'parent_id' => 18,
                'order' => 8,
                'created_at' => '2021-12-27 10:33:17',
                'updated_at' => '2022-01-20 15:25:19',
                'route' => 'reports.social_security.personal.caratula.index',
                'parameters' => NULL,
            ),
            40 => 
            array (
                'id' => 42,
                'menu_id' => 1,
                'title' => 'Imprimir tickets',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-ticket',
                'color' => '#000000',
                'parent_id' => 14,
                'order' => 5,
                'created_at' => '2021-12-27 15:05:43',
                'updated_at' => '2022-01-17 00:30:30',
                'route' => 'cashiers.tickets.generate',
                'parameters' => 'null',
            ),
            41 => 
            array (
                'id' => 43,
                'menu_id' => 1,
                'title' => 'Planillas manuales',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-edit',
                'color' => '#000000',
                'parent_id' => 18,
                'order' => 5,
                'created_at' => '2021-12-28 09:20:17',
                'updated_at' => '2022-01-20 15:25:19',
                'route' => 'reports.social_security.spreadsheets.payments.index',
                'parameters' => 'null',
            ),
            42 => 
            array (
                'id' => 44,
                'menu_id' => 1,
                'title' => 'Personas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-people',
                'color' => NULL,
                'parent_id' => 45,
                'order' => 1,
                'created_at' => '2021-12-29 16:09:13',
                'updated_at' => '2021-12-30 09:23:14',
                'route' => 'voyager.people.index',
                'parameters' => NULL,
            ),
            43 => 
            array (
                'id' => 45,
                'menu_id' => 1,
                'title' => 'Contrataciones',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-file-text',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 5,
                'created_at' => '2021-12-30 09:23:07',
                'updated_at' => '2022-02-07 21:22:45',
                'route' => NULL,
                'parameters' => '',
            ),
            44 => 
            array (
                'id' => 46,
                'menu_id' => 1,
                'title' => 'Programas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-documentation',
                'color' => NULL,
                'parent_id' => 45,
                'order' => 2,
                'created_at' => '2022-01-04 10:56:31',
                'updated_at' => '2022-01-04 10:58:27',
                'route' => 'voyager.programs.index',
                'parameters' => NULL,
            ),
            45 => 
            array (
                'id' => 47,
                'menu_id' => 1,
                'title' => 'Contratos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-certificate',
                'color' => '#000000',
                'parent_id' => 45,
                'order' => 4,
                'created_at' => '2022-01-05 16:27:41',
                'updated_at' => '2022-01-17 16:20:55',
                'route' => 'contracts.index',
                'parameters' => NULL,
            ),
            46 => 
            array (
                'id' => 48,
                'menu_id' => 1,
                'title' => 'Planillas Adicionales',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-dollar',
                'color' => '#000000',
                'parent_id' => 14,
                'order' => 3,
                'created_at' => '2022-01-17 00:29:10',
                'updated_at' => '2022-01-17 00:31:18',
                'route' => 'planillas_adicionales.index',
                'parameters' => 'null',
            ),
            47 => 
            array (
                'id' => 49,
                'menu_id' => 1,
                'title' => 'Tipos de trámite',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-documentation',
                'color' => NULL,
                'parent_id' => 45,
                'order' => 3,
                'created_at' => '2022-01-17 16:09:20',
                'updated_at' => '2022-01-17 16:20:55',
                'route' => 'voyager.procedure-types.index',
                'parameters' => NULL,
            ),
            48 => 
            array (
                'id' => 50,
                'menu_id' => 1,
                'title' => 'Cheques',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-tag',
                'color' => '#000000',
                'parent_id' => 18,
                'order' => 4,
                'created_at' => '2022-01-20 12:04:54',
                'updated_at' => '2022-01-20 15:25:19',
                'route' => 'reports.social_security.personal.checks.index',
                'parameters' => NULL,
            ),
            49 => 
            array (
                'id' => 51,
                'menu_id' => 1,
                'title' => 'Paises',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-window-list',
                'color' => NULL,
                'parent_id' => 29,
                'order' => 5,
                'created_at' => '2022-01-27 14:07:36',
                'updated_at' => '2022-01-27 14:46:51',
                'route' => 'voyager.countries.index',
                'parameters' => NULL,
            ),
            50 => 
            array (
                'id' => 52,
                'menu_id' => 1,
                'title' => 'Departamentos/Estados',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-window-list',
                'color' => NULL,
                'parent_id' => 29,
                'order' => 6,
                'created_at' => '2022-01-27 14:09:15',
                'updated_at' => '2022-01-27 14:46:51',
                'route' => 'voyager.states.index',
                'parameters' => NULL,
            ),
            51 => 
            array (
                'id' => 53,
                'menu_id' => 1,
                'title' => 'Ciudades',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-window-list',
                'color' => NULL,
                'parent_id' => 29,
                'order' => 7,
                'created_at' => '2022-01-27 14:14:05',
                'updated_at' => '2022-01-27 14:46:51',
                'route' => 'voyager.cities.index',
                'parameters' => NULL,
            ),
            52 => 
            array (
                'id' => 54,
                'menu_id' => 1,
                'title' => 'Oficinas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-home',
                'color' => NULL,
                'parent_id' => 29,
                'order' => 4,
                'created_at' => '2022-01-27 14:46:34',
                'updated_at' => '2022-01-27 14:46:51',
                'route' => 'voyager.offices.index',
                'parameters' => NULL,
            ),
            53 => 
            array (
                'id' => 55,
                'menu_id' => 1,
                'title' => 'Firmas autorizadas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-edit',
                'color' => NULL,
                'parent_id' => 29,
                'order' => 8,
                'created_at' => '2022-02-13 15:20:26',
                'updated_at' => '2022-02-13 15:20:46',
                'route' => 'voyager.signatures.index',
                'parameters' => NULL,
            ),
        ));
        
        
    }
}
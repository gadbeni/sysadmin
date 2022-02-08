<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'key' => 'site.title',
                'display_name' => 'Site Title',
                'value' => 'SYSADMIN',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Site',
            ),
            1 => 
            array (
                'id' => 2,
                'key' => 'site.description',
                'display_name' => 'Site Description',
                'value' => 'Sistema Integral de información GADBENI',
                'details' => '',
                'type' => 'text',
                'order' => 2,
                'group' => 'Site',
            ),
            2 => 
            array (
                'id' => 3,
                'key' => 'site.logo',
                'display_name' => 'Site Logo',
                'value' => '',
                'details' => '',
                'type' => 'image',
                'order' => 3,
                'group' => 'Site',
            ),
            3 => 
            array (
                'id' => 4,
                'key' => 'site.google_analytics_tracking_id',
                'display_name' => 'Google Analytics Tracking ID',
                'value' => NULL,
                'details' => '',
                'type' => 'text',
                'order' => 4,
                'group' => 'Site',
            ),
            4 => 
            array (
                'id' => 5,
                'key' => 'admin.bg_image',
                'display_name' => 'Admin Background Image',
                'value' => '',
                'details' => '',
                'type' => 'image',
                'order' => 4,
                'group' => 'Admin',
            ),
            5 => 
            array (
                'id' => 6,
                'key' => 'admin.title',
                'display_name' => 'Admin Title',
                'value' => 'SYSADMIN',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Admin',
            ),
            6 => 
            array (
                'id' => 7,
                'key' => 'admin.description',
                'display_name' => 'Admin Description',
                'value' => 'Sistema Integral de información GADBENI',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Admin',
            ),
            7 => 
            array (
                'id' => 8,
                'key' => 'admin.loader',
                'display_name' => 'Admin Loader',
                'value' => '',
                'details' => '',
                'type' => 'image',
                'order' => 2,
                'group' => 'Admin',
            ),
            8 => 
            array (
                'id' => 9,
                'key' => 'admin.icon_image',
                'display_name' => 'Admin Icon Image',
                'value' => '',
                'details' => '',
                'type' => 'image',
                'order' => 3,
                'group' => 'Admin',
            ),
            9 => 
            array (
                'id' => 10,
                'key' => 'admin.google_analytics_client_id',
            'display_name' => 'Google Analytics Client ID (used for admin dashboard)',
                'value' => NULL,
                'details' => '',
                'type' => 'text',
                'order' => 5,
                'group' => 'Admin',
            ),
            10 => 
            array (
                'id' => 11,
                'key' => 'plantillas.navidad',
                'display_name' => 'Navidad',
                'value' => '0',
                'details' => NULL,
                'type' => 'checkbox',
                'order' => 6,
                'group' => 'Plantillas',
            ),
            11 => 
            array (
                'id' => 12,
                'key' => 'auxiliares.numero_ticket',
                'display_name' => 'Número de ticket',
                'value' => '136',
                'details' => NULL,
                'type' => 'text',
                'order' => 7,
                'group' => 'Auxiliares',
            ),
            12 => 
            array (
                'id' => 13,
                'key' => 'auxiliares.fondo_tickets',
                'display_name' => 'Fondo de pantalla de tickets',
                'value' => 'settings/December2021/d3XrQTBY8KbsxeI6xB1w.jpeg',
                'details' => NULL,
                'type' => 'image',
                'order' => 8,
                'group' => 'Auxiliares',
            ),
            13 => 
            array (
                'id' => 14,
                'key' => 'auxiliares.marquesina',
                'display_name' => 'Marquesina',
            'value' => '"Todo lo que hagan, háganlo de buena gana, como si estuvieran sirviendo al Señor Jesucristo y no a la gente". (Col. 3:23)',
                'details' => NULL,
                'type' => 'text_area',
                'order' => 9,
                'group' => 'Auxiliares',
            ),
            14 => 
            array (
                'id' => 15,
                'key' => 'firma-autorizada.name',
                'display_name' => 'Nombre',
                'value' => 'Geisel Marcelo Oliva Ruiz',
                'details' => NULL,
                'type' => 'text',
                'order' => 10,
                'group' => 'Firma Autorizada',
            ),
            15 => 
            array (
                'id' => 16,
                'key' => 'firma-autorizada.ci',
                'display_name' => 'CI',
                'value' => NULL,
                'details' => NULL,
                'type' => 'text',
                'order' => 11,
                'group' => 'Firma Autorizada',
            ),
            16 => 
            array (
                'id' => 17,
                'key' => 'firma-autorizada.job',
                'display_name' => 'Cargo',
                'value' => 'Secretario Departamental de Administración y Finanzas GADBENI',
                'details' => NULL,
                'type' => 'text',
                'order' => 12,
                'group' => 'Firma Autorizada',
            ),
            17 => 
            array (
                'id' => 18,
                'key' => 'firma-autorizada.designation',
                'display_name' => 'Designación',
                'value' => '01/2021',
                'details' => NULL,
                'type' => 'text',
                'order' => 13,
                'group' => 'Firma Autorizada',
            ),
        ));
        
        
    }
}
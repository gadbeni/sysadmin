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
                'value' => 'MAMORÉ',
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
                'value' => 'Sistema Integral de manejo de información GADBENI',
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
                'id' => 5,
                'key' => 'admin.bg_image',
                'display_name' => 'Admin Background Image',
                'value' => '',
                'details' => '',
                'type' => 'image',
                'order' => 4,
                'group' => 'Admin',
            ),
            4 => 
            array (
                'id' => 6,
                'key' => 'admin.title',
                'display_name' => 'Admin Title',
                'value' => 'MAMORÉ',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Admin',
            ),
            5 => 
            array (
                'id' => 7,
                'key' => 'admin.description',
                'display_name' => 'Admin Description',
                'value' => 'Sistema Integral de manejo de información GADBENI',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Admin',
            ),
            6 => 
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
            7 => 
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
            8 => 
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
            9 => 
            array (
                'id' => 12,
                'key' => 'auxiliares.numero_ticket',
                'display_name' => 'Número de ticket',
                'value' => '1',
                'details' => NULL,
                'type' => 'text',
                'order' => 7,
                'group' => 'Auxiliares',
            ),
            10 => 
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
            11 => 
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
            12 => 
            array (
                'id' => 15,
                'key' => 'firma-autorizada.name',
                'display_name' => 'Nombre',
                'value' => 'LIC.FREDDY MACHADO FLORES',
                'details' => NULL,
                'type' => 'text',
                'order' => 10,
                'group' => 'Firma Autorizada',
            ),
            13 => 
            array (
                'id' => 16,
                'key' => 'firma-autorizada.ci',
                'display_name' => 'CI',
                'value' => '3353449',
                'details' => NULL,
                'type' => 'text',
                'order' => 11,
                'group' => 'Firma Autorizada',
            ),
            14 => 
            array (
                'id' => 17,
                'key' => 'firma-autorizada.job',
                'display_name' => 'Cargo',
                'value' => 'Secretario Dptal. de Administración y Finanzas',
                'details' => NULL,
                'type' => 'text',
                'order' => 12,
                'group' => 'Firma Autorizada',
            ),
            15 => 
            array (
                'id' => 18,
                'key' => 'firma-autorizada.designation',
                'display_name' => 'Decreto de designación',
                'value' => '12/2023',
                'details' => NULL,
                'type' => 'text',
                'order' => 13,
                'group' => 'Firma Autorizada',
            ),
            16 => 
            array (
                'id' => 19,
                'key' => 'planillas.minimum_salary',
                'display_name' => 'Salario mínimo',
                'value' => '2362',
                'details' => NULL,
                'type' => 'text',
                'order' => 14,
                'group' => 'Planillas',
            ),
            17 => 
            array (
                'id' => 20,
                'key' => 'firma-autorizada.job-alt',
                'display_name' => 'Cargo alterno',
                'value' => 'Responsable de Proceso de Contratación de Apoyo Nacional a la Producción y Empleo - RPA',
                'details' => NULL,
                'type' => 'text',
                'order' => 16,
                'group' => 'Firma Autorizada',
            ),
            18 => 
            array (
                'id' => 21,
                'key' => 'firma-autorizada.designation-alt',
                'display_name' => 'Decreto de designación alterna',
                'value' => '12/2023',
                'details' => NULL,
                'type' => 'text',
                'order' => 17,
                'group' => 'Firma Autorizada',
            ),
            19 => 
            array (
                'id' => 22,
                'key' => 'firma-autorizada.designation-date',
                'display_name' => 'Fecha de designación',
                'value' => '28 de febrero de 2023',
                'details' => NULL,
                'type' => 'text',
                'order' => 15,
                'group' => 'Firma Autorizada',
            ),
            20 => 
            array (
                'id' => 23,
                'key' => 'firma-autorizada.designation-date-alt',
                'display_name' => 'Fecha de designación alterna',
                'value' => '28 de febrero de 2023',
                'details' => NULL,
                'type' => 'text',
                'order' => 18,
                'group' => 'Firma Autorizada',
            ),
            21 => 
            array (
                'id' => 24,
                'key' => 'auxiliares.edit-docs',
                'display_name' => 'Editar documentos',
                'value' => '1',
                'details' => NULL,
                'type' => 'checkbox',
                'order' => 19,
                'group' => 'Auxiliares',
            ),
            22 => 
            array (
                'id' => 26,
                'key' => 'auxiliares.enable_all_jobs_for_contract',
                'display_name' => 'Habilitar todos los cargos al crear contrato',
                'value' => '0',
                'details' => NULL,
                'type' => 'checkbox',
                'order' => 23,
                'group' => 'Auxiliares',
            ),
            23 => 
            array (
                'id' => 27,
                'key' => 'servidores.whatsapp',
                'display_name' => 'Whatsapp',
                'value' => NULL,
                'details' => NULL,
                'type' => 'text',
                'order' => 22,
                'group' => 'Servidores',
            ),
            24 => 
            array (
                'id' => 28,
                'key' => 'auxiliares.enable_all_people_for_contract',
                'display_name' => 'Habilitar todos los funcionarios al crear contrato',
                'value' => '0',
                'details' => NULL,
                'type' => 'checkbox',
                'order' => 20,
                'group' => 'Auxiliares',
            ),
        ));
        
        
    }
}
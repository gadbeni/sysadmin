<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class JobsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('jobs')->delete();
        
        \DB::table('jobs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Gobernador del Dpto del Beni',
                'level' => 1,
                'direccion_administrativa_id' => 9,
                'salary' => '17250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Asesores Especializados 1',
                'level' => 3,
                'direccion_administrativa_id' => 9,
                'salary' => '11500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Asesores Especializados 2',
                'level' => 3,
                'direccion_administrativa_id' => 9,
                'salary' => '11500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Asesores Especializados 3',
                'level' => 3,
                'direccion_administrativa_id' => 9,
                'salary' => '11500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Asesores Especializados 4',
                'level' => 3,
                'direccion_administrativa_id' => 9,
                'salary' => '11500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Director/a Gabinete de Gobernación',
                'level' => 4,
                'direccion_administrativa_id' => 9,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Director/a de Auditoria Interna',
                'level' => 4,
                'direccion_administrativa_id' => 9,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Director/a de Interacción Social',
                'level' => 4,
                'direccion_administrativa_id' => 9,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Director/a de Coordinación de Movimientos  Sociales ',
                'level' => 4,
                'direccion_administrativa_id' => 9,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Director/a Notaria de Gobierno ',
                'level' => 4,
                'direccion_administrativa_id' => 9,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Jefe de Unidad II- Auditoria Interna ',
                'level' => 5,
                'direccion_administrativa_id' => 9,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Jefe de Unidad III- Control Servicios Desconcentrados  ',
                'level' => 6,
                'direccion_administrativa_id' => 9,
                'salary' => '6300.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Secretaria General de Gobernación ',
                'level' => 2,
                'direccion_administrativa_id' => 8,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Dirección de Coordinación Municipal',
                'level' => 4,
                'direccion_administrativa_id' => 8,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Director/a de Seguridad Ciudadana',
                'level' => 4,
                'direccion_administrativa_id' => 8,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Director/a de Gestión de Riesgo',
                'level' => 4,
                'direccion_administrativa_id' => 8,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Director/a de Fronteras-Guayaramerín ',
                'level' => 4,
                'direccion_administrativa_id' => 8,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Jefe de Unidad II- Administración y Finanzas',
                'level' => 5,
                'direccion_administrativa_id' => 8,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Jefe de Unidad II- Jurídica',
                'level' => 5,
                'direccion_administrativa_id' => 8,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Secretario/a Dptal. de Planificación y Des. Económico',
                'level' => 2,
                'direccion_administrativa_id' => 13,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Director/a Desarrollo Organizacional ',
                'level' => 4,
                'direccion_administrativa_id' => 13,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Director/a Relacionamiento Internacional',
                'level' => 4,
                'direccion_administrativa_id' => 13,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'Director/a Planificación Estratégica',
                'level' => 4,
                'direccion_administrativa_id' => 13,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'Director/a Inversión Publica ',
                'level' => 4,
                'direccion_administrativa_id' => 13,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'Director/a Programación de Operaciones ',
                'level' => 4,
                'direccion_administrativa_id' => 13,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'Director/a Ordenamiento Territorial y Limites  ',
                'level' => 4,
                'direccion_administrativa_id' => 13,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'Jefe de Unidad II- Administración y Finanzas',
                'level' => 5,
                'direccion_administrativa_id' => 13,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'Jefe de sección I',
                'level' => 7,
                'direccion_administrativa_id' => 13,
                'salary' => '5750.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'Secretaria I',
                'level' => 15,
                'direccion_administrativa_id' => 13,
                'salary' => '3650.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'Secretario/a Dptal. de Desarrollo Amazónico',
                'level' => 2,
                'direccion_administrativa_id' => 37,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'Jefe de Unidad II-Desarrollo Económico',
                'level' => 12,
                'direccion_administrativa_id' => 37,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'Jefe de Unidad II-Administración Y Finanzas ',
                'level' => 17,
                'direccion_administrativa_id' => 37,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'Secretario/a Dptal de Medio Ambiente y Recursos Naturales',
                'level' => 2,
                'direccion_administrativa_id' => 41,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'Director/a Gestion Ambiental y Biodiversidad',
                'level' => 4,
                'direccion_administrativa_id' => 41,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'Jefe de Unidad II- Previsión y Control Ambiental',
                'level' => 5,
                'direccion_administrativa_id' => 41,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'Jefe de Unidad II- Desarrollo Forestal',
                'level' => 5,
                'direccion_administrativa_id' => 41,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'Secretario/a Dptal. de Desarrollo Productivo y Economía Plural',
                'level' => 2,
                'direccion_administrativa_id' => 61,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'Director/a Ganadería ',
                'level' => 4,
                'direccion_administrativa_id' => 61,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'Director/a Desarrollo de Comunidades Organizacionales ',
                'level' => 4,
                'direccion_administrativa_id' => 61,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'Director/a Dptal. de Turismo',
                'level' => 4,
                'direccion_administrativa_id' => 61,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'Director/a Servicio Dptal. Agropecuario Ganadero SEDAG',
                'level' => 4,
                'direccion_administrativa_id' => 61,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'Jefe de Unidad II- Administración y Finanzas',
                'level' => 5,
                'direccion_administrativa_id' => 61,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'Jefe de Unidad II- de Riego',
                'level' => 5,
                'direccion_administrativa_id' => 61,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'Secretario/a Dptal. de Desarrollo Campesino',
                'level' => 2,
                'direccion_administrativa_id' => 6,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'Director/a de Apoyo a la Unidad Productiva Familiar',
                'level' => 4,
                'direccion_administrativa_id' => 6,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            45 => 
            array (
                'id' => 46,
                'name' => 'Jefe de Unidad II-Administración Y Finanzas ',
                'level' => 5,
                'direccion_administrativa_id' => 6,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'Jefe de Unidad II-Fortalecimiento a Procesos Organizativos',
                'level' => 5,
                'direccion_administrativa_id' => 6,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'Jefe de Unidad II-Producción Alimentaria ',
                'level' => 6,
                'direccion_administrativa_id' => 6,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'Secretaria de Minería',
                'level' => 2,
                'direccion_administrativa_id' => 69,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            49 => 
            array (
                'id' => 50,
                'name' => 'Dirección de Energía e Hidrocarburo',
                'level' => 4,
                'direccion_administrativa_id' => 69,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            50 => 
            array (
                'id' => 51,
                'name' => 'Dirección de Minería ',
                'level' => 4,
                'direccion_administrativa_id' => 69,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            51 => 
            array (
                'id' => 52,
                'name' => 'Secretario/a Dptal. de Administración y Finanzas',
                'level' => 2,
                'direccion_administrativa_id' => 16,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            52 => 
            array (
                'id' => 53,
                'name' => 'Asesor Especializado III-SADF',
                'level' => 4,
                'direccion_administrativa_id' => 16,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            53 => 
            array (
                'id' => 54,
                'name' => 'Asesor Especializado III-Finanzas',
                'level' => 4,
                'direccion_administrativa_id' => 16,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            54 => 
            array (
                'id' => 55,
                'name' => 'Director/a Dptal. de Finanzas ',
                'level' => 4,
                'direccion_administrativa_id' => 16,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            55 => 
            array (
                'id' => 56,
                'name' => 'Director/a Dptal. Administrativo ',
                'level' => 4,
                'direccion_administrativa_id' => 16,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            56 => 
            array (
                'id' => 57,
                'name' => 'Director/a Dptal. Recursos Humanos ',
                'level' => 4,
                'direccion_administrativa_id' => 16,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            57 => 
            array (
                'id' => 58,
                'name' => 'Director/a Dptal. Bienestar Laboral y Previsión Social ',
                'level' => 4,
                'direccion_administrativa_id' => 16,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            58 => 
            array (
                'id' => 59,
                'name' => 'Jefe de Unidad II -Jurídico SDAF',
                'level' => 5,
                'direccion_administrativa_id' => 16,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            59 => 
            array (
                'id' => 60,
                'name' => 'Jefe de Unidad II -Archivo  SDAF',
                'level' => 5,
                'direccion_administrativa_id' => 16,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            60 => 
            array (
                'id' => 61,
                'name' => 'Jefe de Unidad II -Sistema y Telecomunicaciones ',
                'level' => 5,
                'direccion_administrativa_id' => 16,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            61 => 
            array (
                'id' => 62,
                'name' => 'Jefe de Unidad II -Recaudación y Control de Ingresos Propios ',
                'level' => 5,
                'direccion_administrativa_id' => 16,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            62 => 
            array (
                'id' => 63,
                'name' => 'Jefe de Unidad II -Presupuesto ',
                'level' => 5,
                'direccion_administrativa_id' => 16,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            63 => 
            array (
                'id' => 64,
                'name' => 'Jefe de Unidad II -Contabilidad y Archivo',
                'level' => 5,
                'direccion_administrativa_id' => 16,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            64 => 
            array (
                'id' => 65,
                'name' => 'Jefe de Unidad II -Tesorería y Crédito Publico ',
                'level' => 5,
                'direccion_administrativa_id' => 16,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            65 => 
            array (
                'id' => 66,
                'name' => 'Jefe de Unidad II -Administrativo',
                'level' => 5,
                'direccion_administrativa_id' => 16,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            66 => 
            array (
                'id' => 67,
                'name' => 'Jefe de Unidad II - Registro y Control de Bienes Públicos',
                'level' => 5,
                'direccion_administrativa_id' => 16,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            67 => 
            array (
                'id' => 68,
                'name' => 'Jefe de Unidad II - Mantenimiento y Reparaciones ',
                'level' => 5,
                'direccion_administrativa_id' => 16,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            68 => 
            array (
                'id' => 69,
                'name' => 'Jefe de Unidad II - Contrataciones de Bienes y Servicios ',
                'level' => 5,
                'direccion_administrativa_id' => 16,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            69 => 
            array (
                'id' => 70,
                'name' => 'Jefe de Unidad II - Administración de Personal ',
                'level' => 5,
                'direccion_administrativa_id' => 16,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            70 => 
            array (
                'id' => 71,
                'name' => 'Jefe de Unidad II - Control y Evaluación  de Personal',
                'level' => 5,
                'direccion_administrativa_id' => 16,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            71 => 
            array (
                'id' => 72,
                'name' => 'Jefe de Unidad III - Contador',
                'level' => 6,
                'direccion_administrativa_id' => 16,
                'salary' => '6300.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            72 => 
            array (
                'id' => 73,
                'name' => 'Jefe Sección  I - Archivo ',
                'level' => 7,
                'direccion_administrativa_id' => 16,
                'salary' => '5750.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            73 => 
            array (
                'id' => 74,
                'name' => 'Jefe de Sección I - Recursos Humanos',
                'level' => 7,
                'direccion_administrativa_id' => 16,
                'salary' => '5750.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            74 => 
            array (
                'id' => 75,
                'name' => 'Jefe de Sección I - Cuenta Contables',
                'level' => 7,
                'direccion_administrativa_id' => 16,
                'salary' => '5750.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            75 => 
            array (
                'id' => 76,
                'name' => 'Jefe de Sección I - Tesorería y Crédito Publico',
                'level' => 7,
                'direccion_administrativa_id' => 16,
                'salary' => '5750.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            76 => 
            array (
                'id' => 77,
                'name' => 'Jefe de Sección I-Responsable de RDE',
                'level' => 7,
                'direccion_administrativa_id' => 16,
                'salary' => '5750.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            77 => 
            array (
                'id' => 78,
                'name' => 'Jefe de Sección I - Memoria Institucional',
                'level' => 7,
                'direccion_administrativa_id' => 16,
                'salary' => '5750.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            78 => 
            array (
                'id' => 79,
                'name' => 'Jefe de Sección I - Dirección de Recaudación y Ingresos Propios',
                'level' => 7,
                'direccion_administrativa_id' => 16,
                'salary' => '5750.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            79 => 
            array (
                'id' => 80,
                'name' => 'Jefe de Sección I - Previsión Social',
                'level' => 7,
                'direccion_administrativa_id' => 16,
                'salary' => '5750.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            80 => 
            array (
                'id' => 81,
                'name' => 'Analista I - D. RR.HH',
                'level' => 7,
                'direccion_administrativa_id' => 16,
                'salary' => '5750.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            81 => 
            array (
                'id' => 82,
                'name' => 'Analista I - Tesorería',
                'level' => 7,
                'direccion_administrativa_id' => 16,
                'salary' => '5750.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            82 => 
            array (
                'id' => 83,
                'name' => 'ANALISTA II-SDAF',
                'level' => 8,
                'direccion_administrativa_id' => 16,
                'salary' => '5500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            83 => 
            array (
                'id' => 84,
                'name' => 'Analista III - Recursos Humanos',
                'level' => 9,
                'direccion_administrativa_id' => 16,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            84 => 
            array (
                'id' => 85,
                'name' => 'Analista IV - Cuentas Contables',
                'level' => 10,
                'direccion_administrativa_id' => 16,
                'salary' => '5000.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            85 => 
            array (
                'id' => 86,
                'name' => 'Analista IV - Bienestar Laboral',
                'level' => 10,
                'direccion_administrativa_id' => 16,
                'salary' => '5000.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            86 => 
            array (
                'id' => 87,
                'name' => 'Técnico I - Dirección de Finanzas',
                'level' => 11,
                'direccion_administrativa_id' => 16,
                'salary' => '4750.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            87 => 
            array (
                'id' => 88,
                'name' => 'Técnico II - Caja',
                'level' => 12,
                'direccion_administrativa_id' => 16,
                'salary' => '4500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            88 => 
            array (
                'id' => 89,
                'name' => 'Técnico III - Administrativo',
                'level' => 13,
                'direccion_administrativa_id' => 16,
                'salary' => '4250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            89 => 
            array (
                'id' => 90,
                'name' => 'Técnico III - Finanzas',
                'level' => 13,
                'direccion_administrativa_id' => 16,
                'salary' => '4250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            90 => 
            array (
                'id' => 91,
                'name' => 'Técnico  III- SDAF',
                'level' => 13,
                'direccion_administrativa_id' => 16,
                'salary' => '4250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            91 => 
            array (
                'id' => 92,
                'name' => 'Asistente I - Bienestar Laboral y Previsión Social',
                'level' => 13,
                'direccion_administrativa_id' => 16,
                'salary' => '4250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            92 => 
            array (
                'id' => 93,
                'name' => 'Asistente II - D.RRHH',
                'level' => 14,
                'direccion_administrativa_id' => 16,
                'salary' => '3950.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            93 => 
            array (
                'id' => 94,
                'name' => 'Secretaria I - Dirección de Finanzas',
                'level' => 15,
                'direccion_administrativa_id' => 16,
                'salary' => '3650.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            94 => 
            array (
                'id' => 95,
                'name' => 'Asistente IV - Aire Acondicionado',
                'level' => 16,
                'direccion_administrativa_id' => 16,
                'salary' => '3400.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            95 => 
            array (
                'id' => 96,
                'name' => 'Asistente IV - Memoria Institucional',
                'level' => 16,
                'direccion_administrativa_id' => 16,
                'salary' => '3400.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            96 => 
            array (
                'id' => 97,
                'name' => 'Secretaria II - Maestranza',
                'level' => 16,
                'direccion_administrativa_id' => 16,
                'salary' => '3400.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            97 => 
            array (
                'id' => 98,
                'name' => 'Auxiliar I - Tesorería y Crédito Publico',
                'level' => 16,
                'direccion_administrativa_id' => 16,
                'salary' => '3400.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            98 => 
            array (
                'id' => 99,
                'name' => 'Auxiliar I-Memoria Institucional',
                'level' => 16,
                'direccion_administrativa_id' => 16,
                'salary' => '3400.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            99 => 
            array (
                'id' => 100,
                'name' => 'Auxiliar/Tramitador II - Secretaria Dptal. de Adm. Finanzas',
                'level' => 17,
                'direccion_administrativa_id' => 16,
                'salary' => '3150.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            100 => 
            array (
                'id' => 101,
                'name' => 'Auxiliar II R.D.E.',
                'level' => 17,
                'direccion_administrativa_id' => 16,
                'salary' => '3150.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            101 => 
            array (
                'id' => 102,
                'name' => 'Auxiliar II- Sereno Portero',
                'level' => 17,
                'direccion_administrativa_id' => 16,
                'salary' => '3150.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            102 => 
            array (
                'id' => 103,
                'name' => 'Auxiliar II - SOLDADOR',
                'level' => 17,
                'direccion_administrativa_id' => 16,
                'salary' => '3150.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            103 => 
            array (
                'id' => 104,
                'name' => 'Sereno- Portero I- Ex-Cordebeni',
                'level' => 17,
                'direccion_administrativa_id' => 16,
                'salary' => '3150.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            104 => 
            array (
                'id' => 105,
                'name' => 'Sereno- Portero I- Ex-Cordebeni',
                'level' => 17,
                'direccion_administrativa_id' => 16,
                'salary' => '3150.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            105 => 
            array (
                'id' => 106,
                'name' => 'Auxiliar  III - SDAF',
                'level' => 18,
                'direccion_administrativa_id' => 16,
                'salary' => '3000.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            106 => 
            array (
                'id' => 107,
                'name' => 'Auxiliar III - Maestranza',
                'level' => 18,
                'direccion_administrativa_id' => 16,
                'salary' => '3000.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            107 => 
            array (
                'id' => 108,
                'name' => 'Auxiliar/Tramitador III - Secretaria Adm.',
                'level' => 18,
                'direccion_administrativa_id' => 16,
                'salary' => '3000.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            108 => 
            array (
                'id' => 109,
                'name' => 'Limpieza II - Secretaria Dptal. de Finanzas',
                'level' => 18,
                'direccion_administrativa_id' => 16,
                'salary' => '3000.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            109 => 
            array (
                'id' => 110,
                'name' => 'Limpieza II - SDAF',
                'level' => 18,
                'direccion_administrativa_id' => 16,
                'salary' => '3000.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            110 => 
            array (
                'id' => 111,
                'name' => 'Secretaria de Desarrollo Indígena ',
                'level' => 2,
                'direccion_administrativa_id' => 64,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            111 => 
            array (
                'id' => 112,
                'name' => 'Director/a Desarrollo Tipnis',
                'level' => 4,
                'direccion_administrativa_id' => 64,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            112 => 
            array (
                'id' => 113,
                'name' => 'Director/a de Manejo y Desarrollo Agrosilvopastoril ',
                'level' => 4,
                'direccion_administrativa_id' => 64,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            113 => 
            array (
                'id' => 114,
                'name' => 'Director/a Gestión Territorial',
                'level' => 4,
                'direccion_administrativa_id' => 64,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            114 => 
            array (
                'id' => 115,
                'name' => 'Jefe de Unidad II- Manejo Flora y Fauna ',
                'level' => 5,
                'direccion_administrativa_id' => 64,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            115 => 
            array (
                'id' => 116,
                'name' => 'Jefe de Unidad II-Soberania y Seguridad Alimentaria ',
                'level' => 5,
                'direccion_administrativa_id' => 64,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            116 => 
            array (
                'id' => 117,
                'name' => 'Jefe de Unidad II-Desarrollo Agropecuario Indígena',
                'level' => 5,
                'direccion_administrativa_id' => 64,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            117 => 
            array (
                'id' => 118,
                'name' => 'Jefe de Unidad II- Cultura y Lengua Originaria ',
                'level' => 5,
                'direccion_administrativa_id' => 64,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            118 => 
            array (
                'id' => 119,
                'name' => 'Jefe de Sección I-Administración y Finanzas',
                'level' => 7,
                'direccion_administrativa_id' => 64,
                'salary' => '5750.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            119 => 
            array (
                'id' => 120,
                'name' => 'Secretario/a Dptal. de Justicia',
                'level' => 2,
                'direccion_administrativa_id' => 10,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            120 => 
            array (
                'id' => 121,
            'name' => 'Director (a) de Defensa Legal Procesal ',
                'level' => 4,
                'direccion_administrativa_id' => 10,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            121 => 
            array (
                'id' => 122,
            'name' => 'Director (a) Procedimientos Jurídicos Administrativos',
                'level' => 4,
                'direccion_administrativa_id' => 10,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            122 => 
            array (
                'id' => 123,
            'name' => 'Director (a) de Coordinación de Justicia y Derechos Ciudadanos',
                'level' => 4,
                'direccion_administrativa_id' => 10,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            123 => 
            array (
                'id' => 124,
            'name' => 'Director (a) de Autonomía y desarrollo Institucional ',
                'level' => 4,
                'direccion_administrativa_id' => 10,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            124 => 
            array (
                'id' => 125,
                'name' => 'Jefe de Unidad II - Coordinación Autonómica ',
                'level' => 5,
                'direccion_administrativa_id' => 10,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            125 => 
            array (
                'id' => 126,
                'name' => 'Jefe de Unidad II -  Control Competencial',
                'level' => 9,
                'direccion_administrativa_id' => 10,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            126 => 
            array (
                'id' => 127,
                'name' => 'Jefe de Unidad II - Proceso Administrativo',
                'level' => 10,
                'direccion_administrativa_id' => 10,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            127 => 
            array (
                'id' => 128,
                'name' => 'Jefe de Unidad II - Desarrollo Legislativo',
                'level' => 13,
                'direccion_administrativa_id' => 10,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            128 => 
            array (
                'id' => 129,
                'name' => 'Secretaria/a Dptal. de Desarrollo Humano',
                'level' => 2,
                'direccion_administrativa_id' => 42,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            129 => 
            array (
                'id' => 130,
            'name' => 'Director (a) de Genero y Asuntos Generacionales ',
                'level' => 4,
                'direccion_administrativa_id' => 42,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            130 => 
            array (
                'id' => 131,
            'name' => 'Director (a) de Educación y Cultura',
                'level' => 4,
                'direccion_administrativa_id' => 42,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            131 => 
            array (
                'id' => 132,
            'name' => 'Director (a) Comité Departamental de persona con Discapacidad del Beni CODEPEDIS ',
                'level' => 4,
                'direccion_administrativa_id' => 42,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            132 => 
            array (
                'id' => 133,
            'name' => 'Director (a) Servicio Departamental de Gestión Social  SEDEGES',
                'level' => 4,
                'direccion_administrativa_id' => 42,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            133 => 
            array (
                'id' => 134,
            'name' => 'Director (a) SEDEDES',
                'level' => 4,
                'direccion_administrativa_id' => 42,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            134 => 
            array (
                'id' => 135,
            'name' => 'Director (a) Servicio Departamental de Salud SEDES-BENI',
                'level' => 4,
                'direccion_administrativa_id' => 42,
                'salary' => '0.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            135 => 
            array (
                'id' => 136,
            'name' => 'Director (a) Hospital German Busch',
                'level' => 4,
                'direccion_administrativa_id' => 42,
                'salary' => '0.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            136 => 
            array (
                'id' => 137,
            'name' => 'Director (a) Hospital Materno Infantil ',
                'level' => 4,
                'direccion_administrativa_id' => 42,
                'salary' => '0.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            137 => 
            array (
                'id' => 138,
            'name' => 'Director (a) Departamental Banco de Sangre ',
                'level' => 4,
                'direccion_administrativa_id' => 42,
                'salary' => '0.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            138 => 
            array (
                'id' => 139,
                'name' => 'Jefe de Unidad II -Administración y Finanzas ',
                'level' => 5,
                'direccion_administrativa_id' => 42,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            139 => 
            array (
                'id' => 140,
                'name' => 'Jefe de Unidad II -  Juventudes ',
                'level' => 5,
                'direccion_administrativa_id' => 42,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            140 => 
            array (
                'id' => 141,
                'name' => 'Jefe de Unidad II - Jurídico ',
                'level' => 5,
                'direccion_administrativa_id' => 42,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            141 => 
            array (
                'id' => 142,
                'name' => 'Jefe de Unidad II - Coordinación ',
                'level' => 5,
                'direccion_administrativa_id' => 42,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            142 => 
            array (
                'id' => 143,
                'name' => 'Jefe de Unidad II - Genero y Generacional ',
                'level' => 5,
                'direccion_administrativa_id' => 42,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            143 => 
            array (
                'id' => 144,
                'name' => 'Jefe de Unidad II -Administración y Finanzas -SEDEGES',
                'level' => 5,
                'direccion_administrativa_id' => 42,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            144 => 
            array (
                'id' => 145,
                'name' => 'Jefe de Unidad II -Coordinadora General ',
                'level' => 5,
                'direccion_administrativa_id' => 42,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            145 => 
            array (
                'id' => 146,
                'name' => 'Jefe de Unidad II - Planificación y Educación ',
                'level' => 5,
                'direccion_administrativa_id' => 42,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            146 => 
            array (
                'id' => 147,
                'name' => 'Jefe de Unidad II – Jurídica SEDEDE',
                'level' => 5,
                'direccion_administrativa_id' => 42,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            147 => 
            array (
                'id' => 148,
                'name' => 'Jefe de Unidad II - Administrativa  SEDEDE',
                'level' => 5,
                'direccion_administrativa_id' => 42,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            148 => 
            array (
                'id' => 149,
                'name' => 'Auxiliar III-SEDEGES ',
                'level' => 18,
                'direccion_administrativa_id' => 42,
                'salary' => '3000.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            149 => 
            array (
                'id' => 150,
                'name' => 'Secretaria/o Dptal. de Obras Publicas ',
                'level' => 2,
                'direccion_administrativa_id' => 55,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            150 => 
            array (
                'id' => 151,
            'name' => 'Director (a) Desarrollo de Obras y Saneamiento Básico ',
                'level' => 4,
                'direccion_administrativa_id' => 55,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            151 => 
            array (
                'id' => 152,
            'name' => 'Director (a) Desarrollo Vial y Transporte ',
                'level' => 4,
                'direccion_administrativa_id' => 55,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            152 => 
            array (
                'id' => 153,
                'name' => 'Jefe de Unidad II - Administración y Finanzas  ',
                'level' => 5,
                'direccion_administrativa_id' => 55,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            153 => 
            array (
                'id' => 154,
                'name' => 'Jefe de Unidad II - Agua y Saneamiento Básico ',
                'level' => 9,
                'direccion_administrativa_id' => 55,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            154 => 
            array (
                'id' => 155,
                'name' => 'Jefe de Unidad II - Electrificación Rural ',
                'level' => 10,
                'direccion_administrativa_id' => 55,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            155 => 
            array (
                'id' => 156,
                'name' => 'Secretaria de Transparencia y Lucha contra a corrupción ',
                'level' => 2,
                'direccion_administrativa_id' => 15,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            156 => 
            array (
                'id' => 157,
                'name' => 'Jefe de Unidad II - SDTLCC',
                'level' => 5,
                'direccion_administrativa_id' => 15,
                'salary' => '6850.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            157 => 
            array (
                'id' => 158,
                'name' => 'Sub-Gobernador -Prov. Cercado',
                'level' => 2,
                'direccion_administrativa_id' => 23,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            158 => 
            array (
                'id' => 159,
                'name' => 'Sub-Gobernador -Prov. Marban',
                'level' => 2,
                'direccion_administrativa_id' => 22,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            159 => 
            array (
                'id' => 160,
                'name' => 'Sub-Gobernador -Prov. Mojos',
                'level' => 2,
                'direccion_administrativa_id' => 39,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            160 => 
            array (
                'id' => 161,
                'name' => 'Sub-Gobernador -Prov. Ballivian',
                'level' => 2,
                'direccion_administrativa_id' => 26,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            161 => 
            array (
                'id' => 162,
                'name' => 'Sub-Gobernador -Prov. Vaca Diez',
                'level' => 2,
                'direccion_administrativa_id' => 40,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            162 => 
            array (
                'id' => 163,
                'name' => 'Sub-Gobernador -Prov. Mamoré',
                'level' => 2,
                'direccion_administrativa_id' => 35,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            163 => 
            array (
                'id' => 164,
                'name' => 'Sub-Gobernador -Prov. Itenez',
                'level' => 2,
                'direccion_administrativa_id' => 25,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            164 => 
            array (
                'id' => 165,
                'name' => 'Sub-Gobernador -Prov. Yacuma',
                'level' => 2,
                'direccion_administrativa_id' => 24,
                'salary' => '12500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            165 => 
            array (
                'id' => 166,
                'name' => 'Analista II.Sub.Gob.Cercado',
                'level' => 5,
                'direccion_administrativa_id' => 23,
                'salary' => '5500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            166 => 
            array (
                'id' => 167,
                'name' => 'Analista II.Sub.Gob.Marban',
                'level' => 5,
                'direccion_administrativa_id' => 22,
                'salary' => '5500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            167 => 
            array (
                'id' => 168,
                'name' => 'Analista  II.Sub.Gob.Mojos',
                'level' => 5,
                'direccion_administrativa_id' => 39,
                'salary' => '5500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            168 => 
            array (
                'id' => 169,
                'name' => 'Analista II.Sub.Gob.Ballivian',
                'level' => 5,
                'direccion_administrativa_id' => 26,
                'salary' => '5500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            169 => 
            array (
                'id' => 170,
                'name' => 'Analista IISub.Gob. Vaca Diez',
                'level' => 5,
                'direccion_administrativa_id' => 40,
                'salary' => '5500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            170 => 
            array (
                'id' => 171,
                'name' => 'Analista II.Sub.Gob. Mamore',
                'level' => 5,
                'direccion_administrativa_id' => 35,
                'salary' => '5500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            171 => 
            array (
                'id' => 172,
                'name' => 'Analista II.Sub.Gob. Itenez',
                'level' => 5,
                'direccion_administrativa_id' => 25,
                'salary' => '5500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            172 => 
            array (
                'id' => 173,
                'name' => 'Analista II Sub.Gob.Yacuma',
                'level' => 5,
                'direccion_administrativa_id' => 24,
                'salary' => '5500.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            173 => 
            array (
                'id' => 174,
                'name' => 'Corregidor de Trinidad',
                'level' => 4,
                'direccion_administrativa_id' => 1,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            174 => 
            array (
                'id' => 175,
                'name' => 'Corregidor de San Javier',
                'level' => 4,
                'direccion_administrativa_id' => 2,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            175 => 
            array (
                'id' => 176,
                'name' => 'Corregidor de Loreto',
                'level' => 4,
                'direccion_administrativa_id' => 28,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            176 => 
            array (
                'id' => 177,
                'name' => 'Corregidor de San Andrés',
                'level' => 4,
                'direccion_administrativa_id' => 34,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            177 => 
            array (
                'id' => 178,
                'name' => 'Corregidor de San Ignacio',
                'level' => 4,
                'direccion_administrativa_id' => 31,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            178 => 
            array (
                'id' => 179,
                'name' => 'Corregidor de San Borja',
                'level' => 4,
                'direccion_administrativa_id' => 49,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            179 => 
            array (
                'id' => 180,
                'name' => 'Corregidor de Rurrenabaque',
                'level' => 4,
                'direccion_administrativa_id' => 43,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            180 => 
            array (
                'id' => 181,
                'name' => 'Corregidor Reyes',
                'level' => 4,
                'direccion_administrativa_id' => 44,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            181 => 
            array (
                'id' => 182,
                'name' => 'Corregidor de Santa Rosa',
                'level' => 4,
                'direccion_administrativa_id' => 47,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            182 => 
            array (
                'id' => 183,
                'name' => 'Corregidor de Riberalta',
                'level' => 4,
                'direccion_administrativa_id' => 46,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            183 => 
            array (
                'id' => 184,
                'name' => 'Corregidor de Guayaramerín',
                'level' => 4,
                'direccion_administrativa_id' => 19,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            184 => 
            array (
                'id' => 185,
                'name' => 'Corregidor de San Ramon',
                'level' => 4,
                'direccion_administrativa_id' => 3,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            185 => 
            array (
                'id' => 186,
                'name' => 'Corregidor de San Joaquin',
                'level' => 4,
                'direccion_administrativa_id' => 4,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            186 => 
            array (
                'id' => 187,
                'name' => 'Corregidor de Puerto Siles',
                'level' => 4,
                'direccion_administrativa_id' => 30,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            187 => 
            array (
                'id' => 188,
                'name' => 'Corregidor de Magdalena',
                'level' => 4,
                'direccion_administrativa_id' => 20,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            188 => 
            array (
                'id' => 189,
                'name' => 'Corregidor de Baures',
                'level' => 4,
                'direccion_administrativa_id' => 21,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            189 => 
            array (
                'id' => 190,
                'name' => 'Corregidor de Huacaraje',
                'level' => 4,
                'direccion_administrativa_id' => 45,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            190 => 
            array (
                'id' => 191,
                'name' => 'Corregidor de Santa Ana',
                'level' => 4,
                'direccion_administrativa_id' => 33,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            191 => 
            array (
                'id' => 192,
                'name' => 'Corregidor de Exaltación',
                'level' => 4,
                'direccion_administrativa_id' => 29,
                'salary' => '9450.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            192 => 
            array (
                'id' => 193,
                'name' => 'Analista III Corregimiento de Trinidad',
                'level' => 6,
                'direccion_administrativa_id' => 1,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            193 => 
            array (
                'id' => 194,
                'name' => 'Analista III Corregimiento de San Javier',
                'level' => 6,
                'direccion_administrativa_id' => 2,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            194 => 
            array (
                'id' => 195,
                'name' => 'Analista III.Corregimiento de  Loreto',
                'level' => 6,
                'direccion_administrativa_id' => 28,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            195 => 
            array (
                'id' => 196,
                'name' => 'Analista III Corregimiento de San Andrés',
                'level' => 6,
                'direccion_administrativa_id' => 34,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            196 => 
            array (
                'id' => 197,
                'name' => 'Analista III Corregimiento de San Ignacio',
                'level' => 6,
                'direccion_administrativa_id' => 31,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            197 => 
            array (
                'id' => 198,
                'name' => 'Analista III Corregimiento de San Borja',
                'level' => 6,
                'direccion_administrativa_id' => 49,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            198 => 
            array (
                'id' => 199,
                'name' => 'Analista III Corregimiento de Rurrenabaque',
                'level' => 6,
                'direccion_administrativa_id' => 43,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            199 => 
            array (
                'id' => 200,
                'name' => 'Analista III Corregimiento de Reyes',
                'level' => 6,
                'direccion_administrativa_id' => 44,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            200 => 
            array (
                'id' => 201,
                'name' => 'Analista III Corregimiento de Santa Rosa',
                'level' => 6,
                'direccion_administrativa_id' => 47,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            201 => 
            array (
                'id' => 202,
                'name' => 'Analista III Corregimiento de Riberalta',
                'level' => 6,
                'direccion_administrativa_id' => 46,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            202 => 
            array (
                'id' => 203,
                'name' => 'Analista III Corregimiento de Guayaramerín',
                'level' => 6,
                'direccion_administrativa_id' => 19,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            203 => 
            array (
                'id' => 204,
                'name' => 'Analista III Corregimiento de San Ramon',
                'level' => 6,
                'direccion_administrativa_id' => 3,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            204 => 
            array (
                'id' => 205,
                'name' => 'Analista III Corregimiento de San Joaquin',
                'level' => 6,
                'direccion_administrativa_id' => 4,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            205 => 
            array (
                'id' => 206,
                'name' => 'Analista III Corregimiento de Puerto Siles',
                'level' => 6,
                'direccion_administrativa_id' => 30,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            206 => 
            array (
                'id' => 207,
                'name' => 'Analista III Corregimiento de Magdalena',
                'level' => 6,
                'direccion_administrativa_id' => 20,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            207 => 
            array (
                'id' => 208,
                'name' => 'Analista III Corregimiento de Baures',
                'level' => 6,
                'direccion_administrativa_id' => 21,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            208 => 
            array (
                'id' => 209,
                'name' => 'Analista III Corregimiento de Huacaraje',
                'level' => 6,
                'direccion_administrativa_id' => 45,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            209 => 
            array (
                'id' => 210,
                'name' => 'Analista III Corregimiento de Santa Ana',
                'level' => 6,
                'direccion_administrativa_id' => 33,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
            210 => 
            array (
                'id' => 211,
                'name' => 'Analista III Corregimiento de Exaltación',
                'level' => 6,
                'direccion_administrativa_id' => 29,
                'salary' => '5250.00',
                'status' => 1,
                'created_at' => '2022-01-07 12:00:00',
                'updated_at' => '2022-01-07 12:00:00',
                'deleted_at' => NULL
            ),
        ));
        
        
    }
}
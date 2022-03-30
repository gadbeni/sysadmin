<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DireccionesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('direcciones')->delete();
        
        \DB::table('direcciones')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'Corregimiento Municipio de Trinidad',
                'sigla' => 'CMT',
                'nit' => 177396029,
                'direccion' => 'San Jose',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'nombre' => 'Corregimiento Municipio de San Javier',
                'sigla' => 'CMSJ',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'nombre' => 'Corregimiento Municipio de San Ramon',
                'sigla' => 'CMSR',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'nombre' => 'Corregimiento Municipio de San Joaquin',
                'sigla' => 'CMSJ',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
            'nombre' => 'Servicio Departamental de Gestion Social (SEDEGES)',
                'sigla' => 'SEDEGES',
                'nit' => 177396029,
                'direccion' => 'Calle Manuel Limpias',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'nombre' => 'Secretaria Departamental de  Desarrollo Campesino',
                'sigla' => 'SDDC',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 2,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 8,
                'nombre' => 'Secretaria de Gobernacion General',
                'sigla' => 'SDGG',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 2,
                'orden' => 5,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 9,
                'nombre' => 'Despacho De Gobernacion',
                'sigla' => 'DEGO',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 1,
                'orden' => 1,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 10,
                'nombre' => 'Secretaria Departamental de Justicia',
                'sigla' => 'SDJ',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 1,
                'orden' => 3,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 11,
                'nombre' => 'Secretaria Departamental de Autonomias y Descentralizacion',
                'sigla' => '',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 1,
                'orden' => 1000,
                'estado' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 12,
                'nombre' => 'Secretaria Departamental Desarrollo Indigena',
                'sigla' => '',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 1,
                'orden' => 1000,
                'estado' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 13,
                'nombre' => 'Secretaria Departamental de Planificacion y Desarrollo Economico',
                'sigla' => 'SPDE',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 2,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 14,
                'nombre' => 'Secretaria Departamental de Auditoria Interna',
                'sigla' => '',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 1,
                'orden' => 1000,
                'estado' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 15,
                'nombre' => 'Secretaria Departamental de Transparencia y Lucha Contra La Corrupcion',
                'sigla' => 'SDTLCC',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 1,
                'orden' => 4,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 16,
                'nombre' => 'Secretaria Departamental de Administracion y Finanzas',
                'sigla' => 'SDAF',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 1,
                'orden' => 2,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 17,
                'nombre' => 'Servicio Descentralizado de Fortalecimiento Municipal y Comunal',
                'sigla' => 'SDFMyC',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 18,
                'nombre' => 'Seguro Universal de Salud Vaca Diez',
                'sigla' => '',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 19,
                'nombre' => 'Corregimiento del Municipio  de Guayaramerin',
                'sigla' => 'CMG',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 20,
                'nombre' => 'Corregimiento de Municipio  Magdalena',
                'sigla' => 'CMM',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 21,
                'nombre' => 'Corregimiento de Municipio  Baures',
                'sigla' => 'CMB',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 22,
                'nombre' => 'Sub Gobernacion Provincia Marban',
                'sigla' => 'SGPM',
                'nit' => 188432024,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 3,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 23,
                'nombre' => 'Sub Gobernacion Provincia Cercado',
                'sigla' => 'SGPC',
                'nit' => 188342020,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 3,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 24,
                'nombre' => 'Sub Gobernacion Provincia Yacuma',
                'sigla' => 'SGPY',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 3,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 25,
                'nombre' => 'Sub Gobernacion Provincia Itenez',
                'sigla' => 'SGPI',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 3,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 26,
                'nombre' => 'Sub Gobernacion Provincia Ballivian',
                'sigla' => 'SGPB',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 3,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 27,
                'nombre' => 'Seguro Universal de Salud SUSA Beni',
                'sigla' => '',
                'nit' => 177396029,
                'direccion' => 'Calle Santa Cruz',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 28,
                'nombre' => 'Corregimiento de Municipio  Loreto',
                'sigla' => 'CML',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 29,
                'nombre' => 'Corregimiento de Municipio  Exaltacion',
                'sigla' => 'CMEX',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 30,
                'nombre' => 'Corregimiento de Municipio  Puerto Siles',
                'sigla' => 'CMPS',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 31,
                'nombre' => 'Corregimiento de Municipio  San Ignacio Moxos',
                'sigla' => 'CMSI',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 32,
            'nombre' => 'Servicio Departamental de Salud (SEDES)',
                'sigla' => 'SEDES',
                'nit' => 1015713025,
                'direccion' => 'Calle la Paz',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 33,
                'nombre' => 'Corregimiento de Municipio  Santa Ana',
                'sigla' => 'CMSA',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 34,
                'nombre' => 'Corregimiento de Municipio  San Andres',
                'sigla' => 'CMSA',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 35,
                'nombre' => 'Sub Gobernacion Provincia Mamore',
                'sigla' => 'SGPM',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 3,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 36,
                'nombre' => 'Hospital Presidente German Busch',
                'sigla' => 'HPGB',
                'nit' => 177396029,
                'direccion' => 'Calle Bolivar',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 37,
                'nombre' => 'Secretaria Departamental de Desarrollo Amazonico',
                'sigla' => 'SDDA',
                'nit' => 177396029,
                'direccion' => 'Riberalta',
                'direcciones_tipo_id' => 2,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            36 => 
            array (
                'id' => 38,
            'nombre' => 'Servicio Departamental Autonomico Agropecuario (SEDAG)',
                'sigla' => '',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 2,
                'orden' => 1000,
                'estado' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            37 => 
            array (
                'id' => 39,
                'nombre' => 'Sub Gobernacion Provincia Moxos',
                'sigla' => 'SGPMX',
                'nit' => 189468024,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 3,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            38 => 
            array (
                'id' => 40,
                'nombre' => 'Sub Gobernacion Provincia Vaca Diez',
                'sigla' => 'SGPVD',
                'nit' => 188764029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 3,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            39 => 
            array (
                'id' => 41,
                'nombre' => 'Secretaria Deparmental de Medio Ambiente y Recursos Naturales',
                'sigla' => 'SDMAyRN',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 2,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            40 => 
            array (
                'id' => 42,
                'nombre' => 'Secretaria Departamental de Desarrollo Humano',
                'sigla' => 'SDDH',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 2,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            41 => 
            array (
                'id' => 43,
                'nombre' => 'Corregimiento de Municipio  Rurrenabaque',
                'sigla' => 'CMRR',
                'nit' => 177396029,
                'direccion' => 'Plaza Principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            42 => 
            array (
                'id' => 44,
                'nombre' => 'Corregimiento del Municipio Reyes',
                'sigla' => 'CMR',
                'nit' => 177396029,
                'direccion' => 'plaza principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            43 => 
            array (
                'id' => 45,
                'nombre' => 'Corregimiento de Municipio de Huacaraje',
                'sigla' => 'CMH',
                'nit' => 177396029,
                'direccion' => 'plaza principal',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            44 => 
            array (
                'id' => 46,
                'nombre' => 'Corregimiento De Municipo Riberalta',
                'sigla' => 'CMRI',
                'nit' => 177396029,
                'direccion' => 'PLAZA PRINCIPAL',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            45 => 
            array (
                'id' => 47,
                'nombre' => 'Corregimiento De Municipio Santa Rosa',
                'sigla' => 'CMSR',
                'nit' => 177396029,
                'direccion' => 'PLAZA PRINCIPAL',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            46 => 
            array (
                'id' => 48,
            'nombre' => 'Servicio Departamental Autonomo de Deportes (SEDEDE)',
                'sigla' => 'SEDEDE',
                'nit' => 177396029,
                'direccion' => 'Estadium mamore',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            47 => 
            array (
                'id' => 49,
                'nombre' => 'Corregimiento De Municipio San Borja ',
                'sigla' => 'CMSB',
                'nit' => 177396029,
                'direccion' => 'PLAZA PRINCIPAL',
                'direcciones_tipo_id' => 4,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            48 => 
            array (
                'id' => 50,
                'nombre' => 'direccion Departamental de Frontera',
                'sigla' => 'DDF',
                'nit' => 177396029,
                'direccion' => 'PLAZA PRINCIPAL',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            49 => 
            array (
                'id' => 53,
                'nombre' => 'Servicio de Banco Sangre',
                'sigla' => 'SBS',
                'nit' => 177396029,
                'direccion' => 'plaza principal',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            50 => 
            array (
                'id' => 55,
                'nombre' => 'Secretaria Departamental Obras Publicas',
                'sigla' => 'SDOP',
                'nit' => 177396029,
                'direccion' => 'plaza principal',
                'direcciones_tipo_id' => 2,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            51 => 
            array (
                'id' => 57,
                'nombre' => 'Hospital Materno Infantil',
                'sigla' => 'HMI',
                'nit' => 177396029,
                'direccion' => 'Frente Stadium Gran Mamore',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            52 => 
            array (
                'id' => 59,
                'nombre' => 'Asamblea Legislativa del Beni ',
                'sigla' => 'ALDB',
                'nit' => 177396029,
                'direccion' => 'Felix Pinto',
                'direcciones_tipo_id' => 2,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            53 => 
            array (
                'id' => 61,
                'nombre' => 'Secretaria Departamental Desarrollo Productivo y Economia Plural',
                'sigla' => 'SDPyEP',
                'nit' => 177396029,
                'direccion' => 'Riberalta',
                'direcciones_tipo_id' => 2,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            54 => 
            array (
                'id' => 62,
            'nombre' => 'direccion Departamental de Gestion de Riesgo (COED)',
                'sigla' => 'COED',
                'nit' => 177396029,
                'direccion' => 'AV. panamericana',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            55 => 
            array (
                'id' => 63,
                'nombre' => 'CODEPEDIS-BENI',
                'sigla' => 'CODEPEDIS',
                'nit' => 1,
                'direccion' => '1',
                'direcciones_tipo_id' => 2,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            56 => 
            array (
                'id' => 64,
                'nombre' => 'Secretaria Dptal. Desarrollo Indigena',
                'sigla' => 'SDDI',
                'nit' => 177,
                'direccion' => 'Plaza principal',
                'direcciones_tipo_id' => 2,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            57 => 
            array (
                'id' => 65,
                'nombre' => 'Dirección Interacción Social ',
                'sigla' => 'DIS',
                'nit' => 177396029,
                'direccion' => 'Plaza principal',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            58 => 
            array (
                'id' => 66,
                'nombre' => 'Dirección Auditoria Interna',
                'sigla' => 'DAI',
                'nit' => 177396029,
                'direccion' => 'Plaza principal',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            59 => 
            array (
                'id' => 67,
                'nombre' => 'Dirección Coordinación Movimientos Sociales',
                'sigla' => 'DCMS',
                'nit' => 177396029,
                'direccion' => 'Plaza principal',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            60 => 
            array (
                'id' => 68,
                'nombre' => 'Dirección Seguridad Ciudadana',
                'sigla' => 'DSC',
                'nit' => 177396029,
                'direccion' => 'Plaza principal',
                'direcciones_tipo_id' => 5,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            61 => 
            array (
                'id' => 69,
                'nombre' => 'Secretaria Departamental De Minería,  Energía e Hidrocarburos',
                'sigla' => '',
                'nit' => 177396029,
                'direccion' => 'Trinidad',
                'direcciones_tipo_id' => 1,
                'orden' => 1000,
                'estado' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
        
    }
}
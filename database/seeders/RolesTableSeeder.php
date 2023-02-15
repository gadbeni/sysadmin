<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        // Root
        $role = Role::firstOrNew(['name' => 'admin']);
        if (!$role->exists) {
            $role->fill(['display_name' => __('voyager::seeders.roles.admin')])->save();
        }

        // Caja
        $role = Role::firstOrNew(['name' => 'caja_jefe_seccion']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Jefe(a) de sección'])->save();
        }

        $role = Role::firstOrNew(['name' => 'caja_tecnico_boveda']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Técnico II Bóveda'])->save();
        }

        $role = Role::firstOrNew(['name' => 'caja_responsable_valores']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Responsable de valores'])->save();
        }

        $role = Role::firstOrNew(['name' => 'caja_cajero']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Cajero(a)'])->save();
        }

        // Previsión social
        $role = Role::firstOrNew(['name' => 'prevision_director']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Director(a)'])->save();
        }

        $role = Role::firstOrNew(['name' => 'prevision_jefe_seccion']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Jefe(a) de sección'])->save();
        }

        $role = Role::firstOrNew(['name' => 'prevision_tecnico']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Técnico'])->save();
        }

        // Recursos humanos
        $role = Role::firstOrNew(['name' => 'rrhh_director']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Director(a)'])->save();
        }

        $role = Role::firstOrNew(['name' => 'rrhh_jefe_unidad']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Jefe(a) de unidad'])->save();
        }

        $role = Role::firstOrNew(['name' => 'rrhh_jefe_seccion']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Jefe(a) de sección'])->save();
        }

        $role = Role::firstOrNew(['name' => 'rrhh_tecnico']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Técnico'])->save();
        }

        $role = Role::firstOrNew(['name' => 'rrhh_tecnico_consultoria']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Técnico'])->save();
        }

        $role = Role::firstOrNew(['name' => 'rrhh_tecnico_planillas']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Técnico planillas'])->save();
        }

        // Administrativo
        $role = Role::firstOrNew(['name' => 'administrativo_director']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Director(a)'])->save();
        }

        $role = Role::firstOrNew(['name' => 'administrativo_jefe_seccion']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Jefe(a) de sección'])->save();
        }

        $role = Role::firstOrNew(['name' => 'administrativo_tecnico']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Técnico'])->save();
        }

        // Contrataciones
        $role = Role::firstOrNew(['name' => 'contrataciones_director']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Director(a)'])->save();
        }

        $role = Role::firstOrNew(['name' => 'contrataciones_jefe_seccion']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Jefe(a) de sección'])->save();
        }

        $role = Role::firstOrNew(['name' => 'contrataciones_tecnico']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Técnico'])->save();
        }

        // Jurídico
        $role = Role::firstOrNew(['name' => 'juridico_director']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Director(a)'])->save();
        }

        $role = Role::firstOrNew(['name' => 'juridico_jefe_seccion']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Jefe(a) de sección'])->save();
        }

        $role = Role::firstOrNew(['name' => 'juridico_tecnico']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Técnico'])->save();
        }
        
        // despacho
        $role = Role::firstOrNew(['name' => 'despacho_gobernador']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Gobernador'])->save();
        }

        // Desconcentrada
        $role = Role::firstOrNew(['name' => 'rrhh_desconcentrada']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Desconcentrada'])->save();
        }

        // COED
        $role = Role::firstOrNew(['name' => 'Coed']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'COED'])->save();
        }

        // Biometrico
        $role = Role::firstOrNew(['name' => 'Biometrico']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Biometrico'])->save();
        }

        // Director de finanzas
        $role = Role::firstOrNew(['name' => 'director_finanzas']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Director de finanzas'])->save();
        }

        // Auditoría interna
        $role = Role::firstOrNew(['name' => 'auditoria_interna']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Auditoría interna'])->save();
        }

        // Direción de finanzas
        $role = Role::firstOrNew(['name' => 'direccion_finanzas_director']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Director'])->save();
        }
        $role = Role::firstOrNew(['name' => 'direccion_finanzas_tecnico']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Técnico'])->save();
        }
    }
}

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
    }
}

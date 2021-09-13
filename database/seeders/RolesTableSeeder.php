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
        $role = Role::firstOrNew(['name' => 'admin']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => __('voyager::seeders.roles.admin'),
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'caja_responsable_boveda']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Responsable de bóveda',
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'caja_cajero']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Cajero(a)',
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'caja_responsable_valores']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Responsable de valores',
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'caja_administrador']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Administrador',
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'prevision_administrador']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Administrador',
            ])->save();
        }
    }
}

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

        $role = Role::firstOrNew(['name' => 'encargado_caja']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Responsable de sección caja',
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'cajero']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Cajero(a)',
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'jefe_seccion_caja']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Jefe de sección caja',
            ])->save();
        }
    }
}

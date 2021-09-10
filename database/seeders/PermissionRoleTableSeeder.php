<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'admin')->firstOrFail();
        $permissions = Permission::all();
        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );

        // Roles de gerente de caja
        $role = Role::where('name', 'encargado_caja')->firstOrFail();
        $permissions = Permission::whereRaw('id = 1 or table_name = "cashiers" or table_name = "vaults"')->get();
        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );

        // Roles de cajero(a)
        $role = Role::where('name', 'cajero')->firstOrFail();
        $permissions = Permission::whereRaw('id = 1 or table_name = "planillas"')->get();
        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );

        // Roles de cajero(a)
        $role = Role::where('name', 'jefe_seccion_caja')->firstOrFail();
        $permissions = Permission::whereRaw('id = 1')->get();
        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );
    }
}

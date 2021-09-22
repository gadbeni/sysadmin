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
        \DB::table('permission_role')->delete();
        
        // Root
        $role = Role::where('name', 'admin')->firstOrFail();
        $permissions = Permission::all();
        $role->permissions()->sync($permissions->pluck('id')->all());

        // Roles de caja
        $role = Role::where('name', 'caja_jefe_seccion')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'caja_responsable_boveda')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or table_name = "cashiers" or table_name = "vaults"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'caja_responsable_valores')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'caja_cajero')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or table_name = "planillas"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        // Roles de previsión social
        $role = Role::where('name', 'prevision_director')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'prevision_jefe_seccion')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or table_name = "social-security"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'prevision_tecnico')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        // Roles de recursos humanos
        $role = Role::where('name', 'rrhh_director')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'rrhh_jefe_unidad')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'rrhh_jefe_seccion')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'rrhh_tecnico')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());
    }
}

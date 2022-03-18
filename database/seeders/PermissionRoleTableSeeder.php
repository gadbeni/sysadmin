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
        $similar_permissions = 'table_name = "admin" or table_name = "cashiers" or table_name = "vaults"';
        $role = Role::where('name', 'caja_jefe_seccion')->firstOrFail();
        $permissions = Permission::whereRaw("   $similar_permissions or
                                                `key` = 'browse_planillaspagos' or
                                                `key` = 'browse_paymentschedules' or
                                                `key` = 'read_paymentschedules' or
                                                `key` = 'enable_paymentschedules' or
                                                `key` = 'close_paymentschedules' or
                                                table_name = 'plugins' or
                                                table_name = 'planillas_adicionales' or
                                                table_name = 'reports_cachiers'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'caja_tecnico_boveda')->firstOrFail();
        $permissions = Permission::whereRaw("   $similar_permissions or
                                                `key` = 'browse_planillaspagos' or
                                                `key` = 'browse_paymentschedules' or
                                                `key` = 'read_paymentschedules' or
                                                `key` = 'enable_paymentschedules' or
                                                `key` = 'close_paymentschedules' or
                                                table_name = 'plugins' or
                                                table_name = 'planillas_adicionales' or
                                                `key` = 'browse_reportscashiervaults'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'caja_responsable_valores')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            `key` = "browse_planillaspagos" or
                                            table_name = "planillas_adicionales" or
                                            table_name = "checks_categories" or
                                            `key` = "browse_checks" or
                                            `key` = "read_checks" or
                                            `key` = "edit_checks" or
                                            `key` = "add_checks" or
                                            `key` = "delete_checks" or
                                            `key` = "payment_checks" or
                                            table_name = "plugins"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'despacho_gobernador')->firstOrFail();
        $permissions = Permission::whereRaw('   table_name = "admin" or
                                                `key` = "browse_checks" or
                                                `key` = "success_checks"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'caja_cajero')->firstOrFail();
        $permissions = Permission::whereRaw('   table_name = "admin" or
                                                `key` = "browse_planillaspagos" or
                                                `key` = "enable_paymentschedules" or
                                                `key` = "close_paymentschedules" or
                                                table_name = "planillas_adicionales" or
                                                table_name = "plugins"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        // Roles de previsión social
        $role = Role::where('name', 'prevision_director')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or table_name = "reports_social_security"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'prevision_jefe_seccion')->firstOrFail();
        $permissions = Permission::whereRaw('   table_name = "admin" or
                                                table_name = "social-security" or
                                                table_name = "dependences" or
                                                table_name = "social_security_types" or
                                                table_name = "checks_beneficiaries" or
                                                table_name = "social-securitypayments" or
                                                table_name = "social-securitychecks" or
                                                table_name = "spreadsheets" or
                                                table_name = "reports_social_security"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'prevision_tecnico')->firstOrFail();
        $permissions = Permission::whereRaw('   table_name = "admin" or
                                                table_name = "social-security" or
                                                table_name = "dependences" or
                                                `key` = "browse_reportssocial-securitypayments" or
                                                `key` = "browse_reportssocial-securityspreadsheetspayments" or
                                                `key` = "browse_reportssocial-securitycaratula" or
                                                `key` = "browse_reportssocial-securitychecks" or
                                                `key` = "browse_social-securitypayments" or
                                                `key` = "read_social-securitypayments" or 
                                                `key` = "add_social-securitypayments" or
                                                `key` = "browse_social-securitychecks" or
                                                `key` = "read_social-securitychecks" or
                                                `key` = "add_social-securitychecks" or
                                                `key` = "browse_spreadsheets" or
                                                `key` = "read_spreadsheets" or
                                                `key` = "add_spreadsheets"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $similar_permissions = 'table_name = "admin" or
                                `key` = "browse_programs" or
                                `key` = "read_programs" or
                                `key` = "browse_people" or
                                `key` = "read_people" or
                                `key` = "edit_people" or
                                `key` = "add_people" or
                                `key` = "browse_contracts" or
                                `key` = "read_contracts" or
                                `key` = "edit_contracts" or
                                `key` = "add_contracts" or
                                `key` = "browse_cities" or
                                `key` = "edit_cities" or
                                `key` = "add_cities"';
        // Roles de recursos humanos
        $role = Role::where('name', 'rrhh_director')->firstOrFail();
        $permissions = Permission::whereRaw("$similar_permissions or
                                            `key` = 'browse_contracts' or
                                            `key` = 'read_contracts' or
                                            `key` = 'edit_contracts' or
                                            `key` = 'add_contracts' or
                                            `key` = 'downgrade_contracts' or
                                            `key` = 'finish_contracts' or
                                            `key` = 'browse_paymentschedules' or
                                            `key` = 'read_paymentschedules' or
                                            `key` = 'add_paymentschedules' or
                                            `key` = 'delete_paymentschedules' or
                                            `key` = 'approve_paymentschedules' or
                                            `key` = 'print_paymentschedules' or
                                            `key` = 'browse_paymentschedulesfiles' or
                                            `key` = 'add_paymentschedulesfiles' or
                                            `key` = 'delete_paymentschedulesfiles' or
                                            table_name = 'reports_rrhh'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'rrhh_jefe_unidad')->firstOrFail();
        $permissions = Permission::whereRaw("$similar_permissions or
                                            `key` = 'browse_contracts' or
                                            `key` = 'read_contracts' or
                                            `key` = 'edit_contracts' or
                                            `key` = 'add_contracts' or
                                            `key` = 'downgrade_contracts' or
                                            `key` = 'finish_contracts' or
                                            `key` = 'browse_paymentschedules' or
                                            `key` = 'read_paymentschedules' or
                                            `key` = 'add_paymentschedules' or
                                            `key` = 'delete_paymentschedules' or
                                            `key` = 'approve_paymentschedules' or
                                            `key` = 'print_paymentschedules' or
                                            `key` = 'browse_paymentschedulesfiles' or
                                            `key` = 'add_paymentschedulesfiles' or
                                            `key` = 'delete_paymentschedulesfiles' or
                                            table_name = 'reports_rrhh'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'rrhh_jefe_seccion')->firstOrFail();
        $permissions = Permission::whereRaw("$similar_permissions or
                                            `key` = 'browse_contracts' or
                                            `key` = 'read_contracts' or
                                            `key` = 'edit_contracts' or
                                            `key` = 'add_contracts' or
                                            `key` = 'downgrade_contracts' or
                                            `key` = 'finish_contracts' or
                                            `key` = 'browse_paymentschedules' or
                                            `key` = 'read_paymentschedules' or
                                            `key` = 'add_paymentschedules' or
                                            `key` = 'delete_paymentschedules' or
                                            `key` = 'approve_paymentschedules' or
                                            `key` = 'print_paymentschedules' or
                                            `key` = 'browse_paymentschedulesfiles' or
                                            `key` = 'add_paymentschedulesfiles' or
                                            `key` = 'delete_paymentschedulesfiles' or
                                            table_name = 'reports_rrhh' or
                                            table_name = 'seniority_bonus_people'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'rrhh_tecnico')->firstOrFail();
        $permissions = Permission::whereRaw("$similar_permissions or
                                            `key` = 'browse_contracts' or
                                            `key` = 'read_contracts' or
                                            `key` = 'edit_contracts' or
                                            `key` = 'add_contracts' or
                                            `key` = 'browse_paymentschedules' or
                                            `key` = 'read_paymentschedules' or
                                            `key` = 'add_paymentschedules' or
                                            `key` = 'delete_paymentschedules' or
                                            `key` = 'approve_paymentschedules' or
                                            `key` = 'print_paymentschedules' or
                                            `key` = 'browse_paymentschedulesfiles' or
                                            `key` = 'add_paymentschedulesfiles' or
                                            `key` = 'delete_paymentschedulesfiles' or
                                            table_name = 'seniority_bonus_people'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        // Roles de administrativo
        $role = Role::where('name', 'administrativo_director')->firstOrFail();
        $permissions = Permission::whereRaw("$similar_permissions or
                                            `key` = 'browse_contracts' or
                                            `key` = 'read_contracts' or
                                            `key` = 'edit_contracts' or
                                            `key` = 'add_contracts' or
                                            `key` = 'downgrade_contracts' or
                                            `key` = 'finish_contracts' or
                                            `key` = 'browse_paymentschedules' or
                                            `key` = 'read_paymentschedules' or
                                            `key` = 'add_paymentschedules' or
                                            `key` = 'delete_paymentschedules' or
                                            `key` = 'approve_paymentschedules' or
                                            `key` = 'print_paymentschedules' or
                                            `key` = 'browse_paymentschedulesfiles' or
                                            `key` = 'add_paymentschedulesfiles' or
                                            `key` = 'delete_paymentschedulesfiles' or
                                            table_name = 'reports_contracts'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'administrativo_jefe_seccion')->firstOrFail();
        $permissions = Permission::whereRaw("$similar_permissions or
                                            `key` = 'browse_contracts' or
                                            `key` = 'read_contracts' or
                                            `key` = 'edit_contracts' or
                                            `key` = 'add_contracts' or
                                            `key` = 'downgrade_contracts' or
                                            `key` = 'finish_contracts' or
                                            `key` = 'browse_paymentschedules' or
                                            `key` = 'read_paymentschedules' or
                                            `key` = 'add_paymentschedules' or
                                            `key` = 'delete_paymentschedules' or
                                            `key` = 'approve_paymentschedules' or
                                            `key` = 'print_paymentschedules' or
                                            `key` = 'browse_paymentschedulesfiles' or
                                            `key` = 'add_paymentschedulesfiles' or
                                            `key` = 'delete_paymentschedulesfiles' or
                                            table_name = 'reports_contracts'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'administrativo_tecnico')->firstOrFail();
        $permissions = Permission::whereRaw("$similar_permissions or
                                            `key` = 'browse_contracts' or
                                            `key` = 'read_contracts' or
                                            `key` = 'edit_contracts' or
                                            `key` = 'add_contracts' or
                                            `key` = 'downgrade_contracts' or
                                            `key` = 'finish_contracts' or
                                            `key` = 'browse_paymentschedules' or
                                            `key` = 'read_paymentschedules' or
                                            `key` = 'add_paymentschedules' or
                                            `key` = 'delete_paymentschedules' or
                                            `key` = 'approve_paymentschedules' or
                                            `key` = 'print_paymentschedules' or
                                            `key` = 'browse_paymentschedulesfiles' or
                                            `key` = 'add_paymentschedulesfiles' or
                                            `key` = 'delete_paymentschedulesfiles'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        // Roles de contrataciones
        $role = Role::where('name', 'contrataciones_director')->firstOrFail();
        $permissions = Permission::whereRaw("$similar_permissions or
                                            `key` = 'browse_contracts' or
                                            `key` = 'read_contracts' or
                                            `key` = 'edit_contracts' or
                                            `key` = 'add_contracts' or
                                            `key` = 'downgrade_contracts' or
                                            `key` = 'finish_contracts' or
                                            `key` = 'browse_paymentschedules' or
                                            `key` = 'read_paymentschedules' or
                                            `key` = 'add_paymentschedules' or
                                            `key` = 'delete_paymentschedules' or
                                            `key` = 'approve_paymentschedules' or
                                            `key` = 'print_paymentschedules' or
                                            `key` = 'browse_paymentschedulesfiles' or
                                            `key` = 'add_paymentschedulesfiles' or
                                            `key` = 'delete_paymentschedulesfiles' or
                                            table_name = 'reports_contracts'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'contrataciones_jefe_seccion')->firstOrFail();
        $permissions = Permission::whereRaw("$similar_permissions or
                                            `key` = 'browse_contracts' or
                                            `key` = 'read_contracts' or
                                            `key` = 'edit_contracts' or
                                            `key` = 'add_contracts' or
                                            `key` = 'downgrade_contracts' or
                                            `key` = 'finish_contracts' or
                                            `key` = 'browse_paymentschedules' or
                                            `key` = 'read_paymentschedules' or
                                            `key` = 'add_paymentschedules' or
                                            `key` = 'delete_paymentschedules' or
                                            `key` = 'approve_paymentschedules' or
                                            `key` = 'print_paymentschedules' or
                                            `key` = 'browse_paymentschedulesfiles' or
                                            `key` = 'add_paymentschedulesfiles' or
                                            `key` = 'delete_paymentschedulesfiles' or
                                            table_name = 'reports_contracts'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'contrataciones_tecnico')->firstOrFail();
        $permissions = Permission::whereRaw("$similar_permissions or
                                            `key` = 'browse_contracts' or
                                            `key` = 'read_contracts' or
                                            `key` = 'edit_contracts' or
                                            `key` = 'add_contracts' or
                                            `key` = 'browse_paymentschedules' or
                                            `key` = 'read_paymentschedules' or
                                            `key` = 'add_paymentschedules' or
                                            `key` = 'delete_paymentschedules' or
                                            `key` = 'approve_paymentschedules' or
                                            `key` = 'print_paymentschedules' or
                                            `key` = 'browse_paymentschedulesfiles' or
                                            `key` = 'add_paymentschedulesfiles' or
                                            `key` = 'delete_paymentschedulesfiles'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        // Roles de jurídico
        $role = Role::where('name', 'juridico_director')->firstOrFail();
        $permissions = Permission::whereRaw("$similar_permissions or
                                            `key` = 'browse_contracts' or
                                            `key` = 'read_contracts' or
                                            `key` = 'edit_contracts' or
                                            `key` = 'add_contracts' or
                                            `key` = 'downgrade_contracts' or
                                            `key` = 'finish_contracts' or
                                            table_name = 'reports_contracts'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'juridico_jefe_seccion')->firstOrFail();
        $permissions = Permission::whereRaw("$similar_permissions or
                                            `key` = 'browse_contracts' or
                                            `key` = 'read_contracts' or
                                            `key` = 'edit_contracts' or
                                            `key` = 'add_contracts' or
                                            `key` = 'downgrade_contracts' or
                                            `key` = 'finish_contracts' or
                                            table_name = 'reports_contracts'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        $role = Role::where('name', 'juridico_tecnico')->firstOrFail();
        $permissions = Permission::whereRaw("$similar_permissions or
                                            `key` = 'browse_contracts' or
                                            `key` = 'read_contracts' or
                                            `key` = 'edit_contracts' or
                                            `key` = 'add_contracts'")->get();
        $role->permissions()->sync($permissions->pluck('id')->all());
    }
}

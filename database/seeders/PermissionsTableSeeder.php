<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        \DB::table('permissions')->delete();
        
        Permission::firstOrCreate([
            'key'        => 'browse_admin',
            'table_name' => 'admin',
        ]);
        
        $keys = [
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
            'browse_clear-cache',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => null,
            ]);
        }
        
        Permission::generateFor('menus');
        Permission::generateFor('roles');
        Permission::generateFor('users');
        Permission::generateFor('permissions');
        Permission::generateFor('settings');

        // Permission::generateFor('planillas');
        Permission::generateFor('cashiers');
        Permission::generateFor('vaults');
        Permission::generateFor('social_security_types');
        Permission::generateFor('checks_beneficiaries');
        Permission::generateFor('dependences');
        Permission::generateFor('social-securitypayments');
        Permission::generateFor('social-securitychecks');
        Permission::generateFor('spreadsheets');
        Permission::generateFor('programs');
        Permission::generateFor('people');
        Permission::generateFor('planillas_adicionales');
        Permission::generateFor('procedure_types');
        Permission::generateFor('contracts');
        Permission::generateFor('countries');
        Permission::generateFor('states');
        Permission::generateFor('cities');
        Permission::generateFor('offices');
        Permission::generateFor('signatures');
        Permission::generateFor('checks_categories');
        Permission::generateFor('checks');
        Permission::generateFor('periods');
        Permission::generateFor('seniority_bonus_types');
        Permission::generateFor('seniority_bonus_people');
        Permission::generateFor('paymentschedules');
        Permission::generateFor('imports');
        Permission::generateFor('irremovability_types');
        Permission::generateFor('memos_types_groups');
        Permission::generateFor('memos_types');
        Permission::generateFor('memos');
        Permission::generateFor('direcciones_tipos');
        Permission::generateFor('person_external_types');
        Permission::generateFor('person_externals');
        Permission::generateFor('places');
        Permission::generateFor('cultures');
        Permission::generateFor('posts');
        Permission::generateFor('donations_types');
        Permission::generateFor('donations');
        Permission::generateFor('afps');
        Permission::generateFor('contracts_alternates_jobs');
        Permission::generateFor('direcciones');
        Permission::generateFor('unidades');
        Permission::generateFor('jobs');
        Permission::generateFor('inbox');
        Permission::generateFor('outbox');
        Permission::generateFor('suggestions');
        Permission::generateFor('assets_subcategories');
        Permission::generateFor('assets_categories');
        Permission::generateFor('assets');

        // people
        $keys = [
            'add_rotation_people',
            'delete_rotation_people',
            'add_irremovability_people',
            'delete_irremovability_people',
            'browse_file_people',
            'add_file_people',
            'edit_file_people',
            'delete_file_people'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'people',
            ]);
        }

        // checkes
        $keys = [
            'upgrade_contracts',
            'downgrade_contracts',
            'finish_contracts',
            'restore_contracts',
            'ratificate_contracts',
            'print_finish_contracts',
            'add_addendum_contracts',
            'edit_addendum_contracts',
            'delete_addendum_contracts',
            'transfer_contracts',
            'promotion_contracts',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'contracts',
            ]);
        }
        
        // checkes
        $keys = [
            'payment_checks',
            'success_checks',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'checks',
            ]);
        }
  
        // Planillas
        $keys = [
            'read_centralize_paymentschedules',
            'send_paymentschedules',
            'approve_paymentschedules',
            'enable_paymentschedules',
            'close_paymentschedules',
            'print_paymentschedules',
            'browse_paymentschedules-files',
            'add_paymentschedules-files',
            'delete_paymentschedules-files',
            'browse_bonuses',
            'add_bonuses',
            'read_bonuses',
            'delete_bonuses'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'paymentschedules',
            ]);
        }
  
        // Pago de planillas
        $keys = [
            'browse_planillaspagos',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'planillas',
            ]);
        }

        $keys = [
            'browse_social-securityprint'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'social-security',
            ]);
        }

        // Reports RRHH
        $keys = [
            'browse_reportshumans-resourcespeople',
            'browse_reportshumans-resourcescontraloria',
            'browse_reportshumans-resourcesaniversarios',
            'browse_reportshumans-resourcesjobs',
            'browse_reportshumans-resourcesrelationships',
            'browse_reportshumans-resourcesprojectsdetails',
            'browse_reportshumans-resourcesbonus',
            'browse_reportspaymentschedulesdetails-status',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'reports_rrhh',
            ]);
        }

        // Reports Contracts
        $keys = [
            'browse_reportsmanagementcontracts',
            'browse_reportsmanagementaddendums',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'reports_contracts',
            ]);
        }

        // Reports social security
        $keys = [
            'browse_reportssocial-securitypayments',
            'browse_reportssocial-securityspreadsheets',
            'browse_reportssocial-securitycontracts',
            'browse_reportssocial-securitypayments-group',
            'browse_reportssocial-securityspreadsheetspayments',
            'browse_reportssocial-securitypersonalpayments',
            'browse_reportssocial-securitycaratula',
            'browse_reportssocial-securitychecks',
            'browse_reportssocial-securityexports',
            'browse_reportssocial-securitypayrollpayments',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'reports_social_security',
            ]);
        }

        // Reports cashier
        $keys = [
            'browse_reportscashiercashiers',
            'browse_reportscashierpayments',
            'browse_reportscashiervaults'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'reports_cachiers',
            ]);
        }

        // Plugins
        $keys = [
            'browse_pluginscashierstickets',
            'browse_pluginscashiersticketsgenerate'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'plugins',
            ]);
        }
    }
}

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

        // checkes
        $keys = [
            'downgrade_contracts',
            'finish_contracts',
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
            'approve_paymentschedules',
            'enable_paymentschedules',
            'close_paymentschedules',
            'print_paymentschedules',
            'browse_paymentschedulesfiles',
            'add_paymentschedulesfiles',
            'delete_paymentschedulesfiles'
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
            'browse_reportshumans-resourcescontraloria',
            'browse_reportshumans-resourcesaniversarios'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'reports_rrhh',
            ]);
        }

        // Reports Contracts
        $keys = [
            'browse_reportscontractscontracts',
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

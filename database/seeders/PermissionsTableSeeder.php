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

        Permission::generateFor('planillas');
        Permission::generateFor('cashiers');
        Permission::generateFor('vaults');
        Permission::generateFor('social_security_types');
        Permission::generateFor('checks_beneficiaries');
        Permission::generateFor('dependences');
        Permission::generateFor('social-securitypayments');
        Permission::generateFor('social-securitychecks');

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

        // Reports social security
        $keys = [
            'browse_reportssocial-securitypayments'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'reports_social_security',
            ]);
        }
    }
}

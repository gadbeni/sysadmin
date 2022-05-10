<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Traits\Seedable;

class DatabaseSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__.'/';

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Se comentó la ejecución de seeders por defecto por motivos de seguridad
        
        // $this->call(VoyagerDatabaseSeeder::class);
        // $this->call(UsersTableSeeder::class);
        // $this->call(MenuItemsTableSeeder::class);
        // $this->call(DataTypesTableSeeder::class);
        // $this->call(DataRowsTableSeeder::class);
        // $this->call(SettingsTableSeeder::class);
        // $this->call(ProcedureTypesTableSeeder::class);
        // $this->call(CountriesTableSeeder::class);
        // $this->call(StatesTableSeeder::class);
        // $this->call(CitiesTableSeeder::class);
        // $this->call(JobsTableSeeder::class);
        // $this->call(ProgramsTableSeeder::class);
        // $this->call(OfficesTableSeeder::class);
        // $this->call(ChecksCategoriesTableSeeder::class);
        // $this->call(SeniorityBonusTypesTableSeeder::class);
        // $this->call(DireccionesTiposTableSeeder::class);
        // $this->call(DireccionesTableSeeder::class);
        // $this->call(UnidadesTableSeeder::class);
        // $this->call(IrremovabilityTypesTableSeeder::class);
    }
}

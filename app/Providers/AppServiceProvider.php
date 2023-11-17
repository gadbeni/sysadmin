<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Pagination\Paginator;

// Formfields
use App\FormFields\DireccionAdministrativaFormField;
use App\FormFields\UserIdFormField;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Voyager::addFormField(DireccionAdministrativaFormField::class);
        Voyager::addFormField(UserIdFormField::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Voyager::addAction(\App\Actions\CashiersAddAmount::class);
        Voyager::addAction(\App\Actions\CashiersClose::class);
        Voyager::addAction(\App\Actions\CashiersPrintOpen::class);
        Voyager::addAction(\App\Actions\CashiersPrintClose::class);
        Voyager::addAction(\App\Actions\SchedulesAssignment::class);

        Paginator::useBootstrap();
    }
}

<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\PlanillasController;
use App\Http\Controllers\CashiersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', function () {
    return redirect('admin/login');
})->name('login');

Route::get('/', function () {
    return redirect('admin');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::post('cashiers/store', [CashiersController::class, 'store'])->name('cashiers.store');
    Route::post('cashiers/amount/store', [CashiersController::class, 'amount_store'])->name('cashiers.amount.store');
    Route::get('cashiers/{cashier}/add-amount', [CashiersController::class, 'add_amount'])->name('cashiers.add.amount');

    Route::get('planillas', [PlanillasController::class, 'planilla_index'])->name('planillas.index');
    Route::post('planillas/search', [PlanillasController::class, 'planilla_search'])->name('planillas.search');
    Route::post('planillas/details/update/status', [PlanillasController::class, 'planilla_details_update_status'])->name('planillas.details.update.status');
    Route::post('planillas/update/status', [PlanillasController::class, 'planilla_update_status'])->name('planillas.update.status');
});

// Clear cache
Route::get('/admin/clear-cache', function() {
    Artisan::call('optimize:clear');
    return redirect('/admin/profile')->with(['message' => 'Cache eliminada.', 'alert-type' => 'success']);
})->name('clear.cache');

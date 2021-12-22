<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlanillasController;
use App\Http\Controllers\CashiersController;
use App\Http\Controllers\VaultsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SocialSecurityController;
use App\Http\Controllers\SpreadsheetsController;
use App\Http\Controllers\PluginsController;

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

Route::get('/', [HomeController::class, 'index']);
Route::post('search', [HomeController::class, 'search_payroll_by_ci'])->name('home.search.payroll.ci');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::post('cashiers/store', [CashiersController::class, 'store'])->name('cashiers.store');
    Route::delete('cashiers/destroy/{id}', [CashiersController::class, 'destroy'])->name('voyager.cashiers.destroy');
    Route::get('cashiers/{cashier}/amount', [CashiersController::class, 'amount'])->name('cashiers.amount');
    Route::post('cashiers/amount/store', [CashiersController::class, 'amount_store'])->name('cashiers.amount.store');
    Route::post('cashiers/{cashier}/change/status', [CashiersController::class, 'change_status'])->name('cashiers.change.status');
    Route::get('cashiers/{cashier}/close/', [CashiersController::class, 'close'])->name('cashiers.close');
    Route::post('cashiers/{cashier}/close/store', [CashiersController::class, 'close_store'])->name('cashiers.close.store');
    Route::post('cashiers/{cashier}/close/revert', [CashiersController::class, 'close_revert'])->name('cashiers.close.revert');
    Route::get('cashiers/{cashier}/confirm_close', [CashiersController::class, 'confirm_close'])->name('cashiers.confirm_close');
    Route::post('cashiers/{cashier}/confirm_close/store', [CashiersController::class, 'confirm_close_store'])->name('cashiers.confirm_close.store');
    Route::get('cashiers/{cashier}/print/open', [CashiersController::class, 'print_open'])->name('print.open');
    Route::get('cashiers/print/transfer/{transfer}', [CashiersController::class, 'print_transfer'])->name('print.transfer');
    Route::get('cashiers/{cashier}/print/close', [CashiersController::class, 'print_close'])->name('print.close');
    Route::get('cashiers/{cashier}/print/payments', [CashiersController::class, 'print_payments'])->name('print.payments');

    Route::resource('vaults', VaultsController::class);
    Route::get('vaults/ajax/list/{id}', [VaultsController::class, 'list']);
    Route::get('vaults/details/{id}', [VaultsController::class, 'view_details'])->name('view.details.show');
    Route::post('vaults/{id}/details/store', [VaultsController::class, 'details_store'])->name('vaults.details.store');
    Route::post('vaults/{id}/open', [VaultsController::class, 'open'])->name('vaults.open');
    Route::get('vaults/{id}/close', [VaultsController::class, 'close'])->name('vaults.close');
    Route::post('vaults/{id}/close/store', [VaultsController::class, 'close_store'])->name('vaults.close.store');
    Route::get('vaults/{vault}/print/income', [VaultsController::class, 'print_income'])->name('vaults.print.income');
    Route::get('vaults/{vault}/print/closure', [VaultsController::class, 'print_closure'])->name('vaults.print.closure');
    Route::get('vaults/{vault}/print/status', [VaultsController::class, 'print_status'])->name('vaults.print.status');

    Route::get('planillas', [PlanillasController::class, 'planilla_index'])->name('planillas.index');
    Route::post('planillas/search', [PlanillasController::class, 'planilla_search'])->name('planillas.search');
    Route::get('planillas/search/id', [PlanillasController::class, 'planilla_search_by_id'])->name('planillas.search.id');
    Route::post('planillas/details/open', [PlanillasController::class, 'planilla_details_open'])->name('planillas.details.open');
    Route::post('planillas/details/payment', [PlanillasController::class, 'planilla_details_payment'])->name('planillas.details.payment');
    Route::post('planillas/details/payment/multiple', [PlanillasController::class, 'planilla_details_payment_multiple'])->name('planillas.details.payment.multiple');
    Route::post('planillas/update/status', [PlanillasController::class, 'planilla_update_status'])->name('planillas.update.status');
    Route::get('planillas/pago/print/{id}', [PlanillasController::class, 'planillas_pago_print']);
    Route::post('planillas/pago/delete', [PlanillasController::class, 'planilla_payment_delete'])->name('planilla.payment.delete');
    Route::get('planillas/pago/delete/print/{id}', [PlanillasController::class, 'planillas_pago_delete_print']);

    Route::post('planillas/centralizada/search', [PlanillasController::class, 'planilla_centralizada_search'])->name('planillas.centralizada.search');

    // Previsión social
    // * Cheques
    Route::get('social-security/checks', [SocialSecurityController::class, 'checks_index'])->name('checks.index');
    Route::get('social-security/checks/list', [SocialSecurityController::class, 'checks_list'])->name('checks.list');
    Route::get('social-security/checks/create', [SocialSecurityController::class, 'checks_create'])->name('checks.create');
    Route::post('social-security/checks/store', [SocialSecurityController::class, 'checks_store'])->name('checks.store');
    Route::get('social-security/checks/{check}', [SocialSecurityController::class, 'checks_show'])->name('checks.show');
    Route::get('social-security/checks/{check}/edit', [SocialSecurityController::class, 'checks_edit'])->name('checks.edit');
    Route::put('social-security/checks/{check}/update', [SocialSecurityController::class, 'checks_update'])->name('checks.update');
    Route::delete('social-security/checks/{check}/delete', [SocialSecurityController::class, 'checks_delete'])->name('checks.delete');
    Route::post('social-security/checks/delete/multiple', [SocialSecurityController::class, 'checks_delete_multiple'])->name('checks.delete_multiple');

    // *Pagos
    Route::get('social-security/payments', [SocialSecurityController::class, 'payments_index'])->name('payments.index');
    Route::get('social-security/payments/list', [SocialSecurityController::class, 'payments_list'])->name('payments.list');
    Route::get('social-security/payments/create', [SocialSecurityController::class, 'payments_create'])->name('payments.create');
    Route::post('social-security/payments/store', [SocialSecurityController::class, 'payments_store'])->name('payments.store');
    Route::get('social-security/payments/{payment}', [SocialSecurityController::class, 'payments_show'])->name('payments.show');
    Route::get('social-security/payments/{payment}/edit', [SocialSecurityController::class, 'payments_edit'])->name('payments.edit');
    Route::put('social-security/payments/{payment}/update', [SocialSecurityController::class, 'payments_update'])->name('payments.update');
    Route::delete('social-security/payments/{payment}/delete', [SocialSecurityController::class, 'payments_delete'])->name('payments.delete');
    Route::post('social-security/payments/update/multiple', [SocialSecurityController::class, 'payments_update_multiple'])->name('payments.update_multiple');
    Route::post('social-security/payments/delete/multiple', [SocialSecurityController::class, 'payments_delete_multiple'])->name('payments.delete_multiple');

    // *Formularios de impresión
    Route::get('social-security/print', [SocialSecurityController::class, 'print_index'])->name('social.security.print.index');
    Route::post('social-security/print/forms/{name}', [SocialSecurityController::class, 'print_form'])->name('print.form');

    // *Planillas
    Route::resource('spreadsheets', SpreadsheetsController::class);
    Route::get('spreadsheets/ajax/list', [SpreadsheetsController::class, 'list']);
    Route::post('spreadsheets/delete/multiple', [SpreadsheetsController::class, 'destroy_multiple'])->name('spreadsheets.delete_multiple');

    // Reportes

    // *Recursos humanos
    Route::get('reports/humans-resources/contraloria', [ReportsController::class, 'humans_resources_contraloria_index'])->name('reports.humans_resources.contraloria');
    Route::post('reports/humans-resources/contraloria/list', [ReportsController::class, 'humans_resources_contraloria_list'])->name('reports.humans_resources.contraloria.list');
    Route::post('reports/humans-resources/contraloria/print', [ReportsController::class, 'humans_resources_contraloria_print'])->name('reports.humans_resources.contraloria.print');

    Route::get('reports/humans-resources/aniversarios', [ReportsController::class, 'humans_resources_aniversarios_index'])->name('reports.humans_resources.aniversarios');
    Route::post('reports/humans-resources/aniversarios/list', [ReportsController::class, 'humans_resources_aniversarios_list'])->name('reports.humans_resources.aniversarios.list');

    // *Previsión social
    Route::get('reports/social-security/payments', [ReportsController::class, 'social_security_payments_index'])->name('reports.social_security.payments');
    Route::post('reports/social-security/payments/list', [ReportsController::class, 'social_security_payments_list'])->name('reports.social_security.payments.list');
    Route::get('reports/social-security/spreadsheets', [ReportsController::class, 'social_security_spreadsheets_index'])->name('reports.social_security.spreadsheets');
    Route::post('reports/social-security/spreadsheets/list', [ReportsController::class, 'social_security_spreadsheets_list'])->name('reports.social_security.spreadsheets.list');
    Route::get('reports/social-security/contracts', [ReportsController::class, 'social_security_contracts_index'])->name('reports.social_security.contracts');
    Route::post('reports/social-security/contracts/list', [ReportsController::class, 'social_security_contracts_list'])->name('reports.social_security.contracts.list');
    Route::get('reports/social-security/payments-group', [ReportsController::class, 'social_security_payments_group_index'])->name('social-security.payments.group.index');
    Route::post('reports/social-security/payments-group', [ReportsController::class, 'social_security_payments_group_list'])->name('social-security.payments.group.list');
    Route::get('reports/social-security/spreadsheets/payments', [ReportsController::class, 'social_security_spreadsheets_payments_index'])->name('reports.social_security.spreadsheets.payments.index');
    Route::post('reports/social-security/spreadsheets/payments/list', [ReportsController::class, 'social_security_spreadsheets_payments_list'])->name('reports.social_security.spreadsheets.payments.list');

    // Cashier
    Route::get('reports/cashier/cashiers', [ReportsController::class, 'cashier_cashiers_index'])->name('reports.cashier.cashiers.index');
    Route::post('reports/cashier/cashiers/list', [ReportsController::class, 'cashier_cashiers_list'])->name('reports.cashier.cashiers.list');
    Route::get('reports/cashier/payments', [ReportsController::class, 'cashier_payments_index'])->name('reports.cashier.payments.index');
    Route::post('reports/cashier/payments/list', [ReportsController::class, 'cashier_payments_list'])->name('reports.cashier.payments.list');
    Route::get('reports/cashier/vaults', [ReportsController::class, 'cashier_vaults_index'])->name('reports.cashier.vaults.index');
    Route::post('reports/cashier/vaults/list', [ReportsController::class, 'cashier_vaults_list'])->name('reports.cashier.vaults.list');

    // Testing
    Route::get('/test/{year}', [SocialSecurityController::class, 'test']);

    // Complementos
    Route::get('plugins/cashiers/tickets', [PluginsController::class, 'cashiers_tickets'])->name('cashiers.tickets');
    Route::post('plugins/cashiers/tickets/set', function(){
        set_setting('auxiliares.numero_ticket', setting('auxiliares.numero_ticket') +1);
        return response()->json(['ticket' => setting('auxiliares.numero_ticket') +1]);
    });
    Route::get('plugins/cashiers/tickets/get', function(){
        return response()->json(['ticket' => setting('auxiliares.numero_ticket')]);
    });
});

// Clear cache
Route::get('/admin/clear-cache', function() {
    Artisan::call('optimize:clear');
    return redirect('/admin/profile')->with(['message' => 'Cache eliminada.', 'alert-type' => 'success']);
})->name('clear.cache');

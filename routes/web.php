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
use App\Http\Controllers\ContractsController;
use App\Http\Controllers\StipendController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\PeriodsController;
use App\Http\Controllers\PaymentschedulesController;
use App\Http\Controllers\ImportsController;

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


Route::group(['prefix' => 'admin', 'middleware' => 'loggin'], function () {
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

    Route::get('planillas/pagos', [PlanillasController::class, 'planillas_pagos_index'])->name('planillas.pagos.index');
    Route::post('planillas/pagos/search', [PlanillasController::class, 'planillas_pagos_search'])->name('planillas.pagos.search');
    Route::get('planillas/pagos/search/id', [PlanillasController::class, 'planillas_pagos_search_by_id']);
    Route::post('planillas/pagos/details/open', [PlanillasController::class, 'planillas_pagos_details_open'])->name('planillas.pagos.details.open');
    Route::post('planillas/pagos/details/payment', [PlanillasController::class, 'planillas_pagos_details_payment'])->name('planillas.pagos.details.payment');
    Route::post('planillas/pagos/details/payment/multiple', [PlanillasController::class, 'planillas_pagos_details_payment_multiple'])->name('planillas.pagos.details.payment.multiple');
    Route::post('planillas/pagos/update/status', [PlanillasController::class, 'planillas_pagos_update_status'])->name('planillas.pagos.update.status');
    Route::get('planillas/pagos/print/{id}', [PlanillasController::class, 'planillas_pagos_print']);
    Route::get('planillas/pagos/aguinaldos/print/{id}', [PlanillasController::class, 'planillas_pagos_aguinaldos_print']);
    Route::post('planillas/pagos/delete', [PlanillasController::class, 'planillas_pagos_delete'])->name('planillas.pagos.delete');
    Route::get('planillas/pagos/delete/print/{id}', [PlanillasController::class, 'planillas_pagos_delete_print']);

    Route::post('planillas/pagos/centralizada/search', [PlanillasController::class, 'planillas_pagos_centralizada_search'])->name('planillas.pagos.centralizada.search');

    // Planillas de pagos
    Route::resource('paymentschedules', PaymentschedulesController::class);
    Route::get('paymentschedules/ajax/list/{search?}', [PaymentschedulesController::class, 'list']);
    Route::post('paymentschedules/generate', [PaymentschedulesController::class, 'generate'])->name('paymentschedules.generate');
    Route::get('paymentschedules/files/index', [PaymentschedulesController::class, 'files_index'])->name('paymentschedules.files.index');
    Route::get('paymentschedules/files/list/{search?}', [PaymentschedulesController::class, 'files_list'])->name('paymentschedules.files.list');
    Route::get('paymentschedules/files/create', [PaymentschedulesController::class, 'files_create'])->name('paymentschedules.files.create');
    Route::post('paymentschedules/files/generate', [PaymentschedulesController::class, 'files_generate'])->name('paymentschedules.files.generate');
    Route::post('paymentschedules/files/store', [PaymentschedulesController::class, 'files_store'])->name('paymentschedules.files.store');
    Route::post('paymentschedules/files/delete', [PaymentschedulesController::class, 'files_delete'])->name('paymentschedules.files.delete');

    // Previsión social
    // * Cheques
    Route::get('social-security/checks', [SocialSecurityController::class, 'checks_index'])->name('social-security.checks.index');
    Route::get('social-security/checks/list/{search?}', [SocialSecurityController::class, 'checks_list'])->name('social-security.checks.list');
    Route::get('social-security/checks/create', [SocialSecurityController::class, 'checks_create'])->name('social-security.checks.create');
    Route::post('social-security/checks/store', [SocialSecurityController::class, 'checks_store'])->name('social-security.checks.store');
    Route::get('social-security/checks/{check}', [SocialSecurityController::class, 'checks_show'])->name('social-security.checks.show');
    Route::get('social-security/checks/{check}/edit', [SocialSecurityController::class, 'checks_edit'])->name('social-security.checks.edit');
    Route::put('social-security/checks/{check}/update', [SocialSecurityController::class, 'checks_update'])->name('social-security.checks.update');
    Route::delete('social-security/checks/{check}/delete', [SocialSecurityController::class, 'checks_delete'])->name('social-security.checks.delete');
    Route::post('social-security/checks/delete/multiple', [SocialSecurityController::class, 'checks_delete_multiple'])->name('social-security.checks.delete_multiple');
    Route::post('social-security/checks/derive', [SocialSecurityController::class, 'checks_derive'])->name('checks.derive');

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



    // PLANILLAS ADICIONALES
    Route::resource('planillas_adicionales', StipendController::class);
    Route::post('planillas_adicionales/update', [StipendController::class, 'update_planilla'])->name('planilla.adicional.update');
    Route::delete('planillas_adicionales/delete', [StipendController::class, 'destroy'])->name('planillas.adicional.delete');

    // CHECK
    Route::resource('checks', CheckController::class);
    Route::post('check/entregar', [CheckController::class, 'entregar_checks'])->name('checks.entregar');
    Route::post('check/update', [CheckController::class, 'update_checks'])->name('checks.updat');
    Route::post('check/devolver', [CheckController::class, 'devolver_checks'])->name('checks.devolver');
    Route::delete('check/delete', [CheckController::class, 'destroy'])->name('checks.delet');

    // Periods
    Route::get('periods/tipo_direccion_adminstrativa/{id}', [PeriodsController::class, 'periods_tipo_direccion_adminstrativa']);

    // Administrativo

    // *Contratos
    Route::resource('contracts', ContractsController::class);
    Route::post('contracts/status', [ContractsController::class, 'contracts_status'])->name('contracts.status');
    Route::get('contracts/direccion-administrativa/{id}', [ContractsController::class, 'contracts_direccion_administrativa']);
    Route::get('contracts/{id}/print/{document}', [ContractsController::class, 'print'])->name('contracts.print');
    
    // Reportes

    // *Recursos humanos
    Route::get('reports/humans-resources/contraloria', [ReportsController::class, 'humans_resources_contraloria_index'])->name('reports.humans_resources.contraloria');
    Route::post('reports/humans-resources/contraloria/list', [ReportsController::class, 'humans_resources_contraloria_list'])->name('reports.humans_resources.contraloria.list');

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
    Route::get('reports/social-security/personal/payments', [ReportsController::class, 'social_security_personal_payments_index'])->name('reports.social_security.personal.payments.index');
    Route::post('reports/social-security/personal/payments/list', [ReportsController::class, 'social_security_personal_payments_list'])->name('reports.social_security.personal.payments.list');
    Route::get('reports/social-security/caratula', [ReportsController::class, 'social_security_personal_caratula_index'])->name('reports.social_security.personal.caratula.index');
    Route::post('reports/social-security/caratula/list', [ReportsController::class, 'social_security_personal_caratula_list'])->name('reports.social_security.personal.caratula.list');
    Route::get('reports/social-security/checks', [ReportsController::class, 'social_security_personal_checks_index'])->name('reports.social_security.personal.checks.index');
    Route::post('reports/social-security/checks/list', [ReportsController::class, 'social_security_personal_checks_list'])->name('reports.social_security.personal.checks.list');

    // Cashier
    Route::get('reports/cashier/cashiers', [ReportsController::class, 'cashier_cashiers_index'])->name('reports.cashier.cashiers.index');
    Route::post('reports/cashier/cashiers/list', [ReportsController::class, 'cashier_cashiers_list'])->name('reports.cashier.cashiers.list');
    Route::get('reports/cashier/payments', [ReportsController::class, 'cashier_payments_index'])->name('reports.cashier.payments.index');
    Route::post('reports/cashier/payments/list', [ReportsController::class, 'cashier_payments_list'])->name('reports.cashier.payments.list');
    Route::get('reports/cashier/vaults', [ReportsController::class, 'cashier_vaults_index'])->name('reports.cashier.vaults.index');
    Route::post('reports/cashier/vaults/list', [ReportsController::class, 'cashier_vaults_list'])->name('reports.cashier.vaults.list');

    // Contrataciones
    Route::get('reports/contracts/contracts', [ReportsController::class, 'contracts_contracts_index'])->name('reports.contracts.contracts.index');
    Route::post('reports/contracts/contracts/list', [ReportsController::class, 'contracts_contracts_list'])->name('reports.contracts.contracts.list');

    
    // Complementos
    Route::get('plugins/cashiers/tickets', [PluginsController::class, 'cashiers_tickets'])->name('cashiers.tickets');
    Route::get('plugins/cashiers/tickets/generate', [PluginsController::class, 'cashiers_tickets_generate'])->name('cashiers.tickets.generate');
    Route::post('plugins/cashiers/tickets/print', [PluginsController::class, 'cashiers_tickets_print'])->name('cashiers.tickets.print');

    Route::post('plugins/cashiers/tickets/set', function(){
        set_setting('auxiliares.numero_ticket', setting('auxiliares.numero_ticket') +1);
        return response()->json(['ticket' => setting('auxiliares.numero_ticket') +1]);
    });
    Route::get('plugins/cashiers/tickets/get', function(){
        return response()->json(['ticket' => setting('auxiliares.numero_ticket')]);
    });

    Route::get('imports', [ImportsController::class, 'imports_index'])->name('imports.index');
    Route::post('imports/store', [ImportsController::class, 'imports_store'])->name('imports.store');
});

// Clear cache
Route::get('/admin/clear-cache', function() {
    Artisan::call('optimize:clear');
    return redirect('/admin/profile')->with(['message' => 'Cache eliminada.', 'alert-type' => 'success']);
})->name('clear.cache');

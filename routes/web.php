<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\MemosController;
use App\Http\Controllers\FinancesController;
use App\Http\Controllers\MemosTypesController;
use App\Http\Controllers\TcInboxController;
use App\Http\Controllers\TcOutboxController;
use App\Http\Controllers\TcController;
use App\Http\Controllers\SeniorityBonusPeopleController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SchedulesController;
use App\Http\Controllers\AssetsControllers;
use App\Http\Controllers\AttendancesController;
use App\Http\Controllers\AttendancePermitsController;
use App\Http\Controllers\ProgramsController;

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
Route::get('/person/{code}', [HomeController::class, 'person']);
Route::post('search', [HomeController::class, 'search_payroll_by_ci'])->name('home.search.payroll.ci');

Route::get('policies', function(){
    return view('policies');
})->name('home.policies');

// Enviar mensaje de whatsapp
Route::post('send-whatsapp', [HomeController::class, 'send_message'])->name('send.whatsapp');
Route::post('send-suggestion', [HomeController::class, 'send_suggestion'])->name('send.suggestion');


Route::group(['prefix' => 'admin', 'middleware' => 'loggin'], function () {
    Voyager::routes();

    // ____________________Trimite y correspondencia_____________________
    Route::resource('inbox', TcInboxController::class);
    Route::get('inbox/list/{funcionario_id}/{type}', [TcInboxController::class, 'derivacion_list']);
    Route::post('inbox/delete/derivacion', [TcInboxController::class, 'bandejaDerivationDelete'])->name('inbox-derivation.delete');
    Route::post('inbox/{id}/archivar', [TcInboxController::class, 'derivacion_archivar'])->name('inbox.archivar');
    Route::post('inbox/store/derivacion', [TcInboxController::class, 'store_derivacion'])->name('inbox.derivacion');
    Route::post('inbox/store/file', [TcInboxController::class, 'store_file']);
    // Route::get('inbox/{id}', [TcInboxController::class, 'derivacion_show'])->name('bandeja.show');

    Route::resource('outbox', TcOutboxController::class);
    Route::get('outbox/ajax/list', [TcOutboxController::class, 'list']);
    Route::post('outbox/store/file', [TcOutboxController::class, 'store_file']);
    Route::get('outbox/{outbox}/print', [TcOutboxController::class, 'print'])->name('outbox.print');
    Route::get('outbox/{outbox}/printhr', [TcOutboxController::class, 'printhr'])->name('outbox.printhr');
    Route::post('outbox/read/nci/file', [TcOutboxController::class, 'entradaFile'])->name('outbox-file-nci.store');
    Route::post('outbox/store/vias', [TcOutboxController::class, 'store_vias'])->name('store.vias');
    Route::post('outbox/nulledvia', [TcOutboxController::class, 'anulacion_via'])->name('via.nulled');
    Route::post('outbox/delete/derivacion', [TcOutboxController::class, 'delete_derivacion'])->name('delete.outbox');
    Route::post('outbox/delete/derivacions', [TcOutboxController::class, 'delete_derivacions'])->name('delete.outboxs');
    Route::post('outbox/delete/derivacion/file', [TcOutboxController::class, 'delete_derivacion_file'])->name('delete.outbox.file');
    Route::post('outbox/store/derivacion', [TcOutboxController::class, 'store_derivacion'])->name('outbox.derivacion');

    Route::get('mamore/getpeoplederivacion',[TcController::class, 'getPeoplesDerivacion'])->name('getpeoplederivacion');
    Route::get('cite/{id?}/{cite?}',[TcController::class,'getCite'])->name('cite.get');

    Route::get('people', [PeopleController::class, 'index'])->name('voyager.people.index');
    Route::get('people/ajax/list/{search?}', [PeopleController::class, 'list']);
    Route::get('people/{id}', [PeopleController::class, 'read'])->name('voyager.people.show');
    Route::post('people/{id}/rotation', [PeopleController::class, 'rotation_store'])->name('people.rotation.store');
    Route::post('people/{id}/file', [PeopleController::class, 'file_store'])->name('people.file.store');
    Route::put('people/{id}/file', [PeopleController::class, 'file_update'])->name('people.file.update');
    Route::delete('people/{people}/file/{id}', [PeopleController::class, 'file_delete'])->name('people.file.delete');
    Route::post('people/{id}/assets', [PeopleController::class, 'assets_store'])->name('people.assets.store');
    Route::get('people/{id}/assets/{person_asset_id}/print', [PeopleController::class, 'assets_print'])->name('people.assets.print');
    Route::get('people/rotation/{id}', [PeopleController::class, 'rotation_print']);
    Route::delete('people/{people}/rotation/{id}', [PeopleController::class, 'rotation_delete'])->name('people.rotation.delete');
    Route::post('people/{id}/irremovability', [PeopleController::class, 'irremovability_store'])->name('people.irremovability.store');
    Route::delete('people/{people}/irremovability/{id}', [PeopleController::class, 'irremovability_delete'])->name('people.irremovability.delete');
    Route::post('people/{id}/afp_status', [PeopleController::class, 'afp_status'])->name('people.afp_status.update');
    
    Route::post('people/{id}/attendance', [PeopleController::class, 'attendances'])->name('people.attendances');
    Route::post('people/{id}/attendance/store', [PeopleController::class, 'attendances_store'])->name('people.attendances.store');
    Route::post('people/{id}/attendance/update', [PeopleController::class, 'attendances_update'])->name('people.attendances.update');
    Route::post('people/{id}/attendance/delete', [PeopleController::class, 'attendances_delete'])->name('people.attendances.delete');
    Route::get('people/ajax/search/{type?}', [PeopleController::class, 'search']);

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
    Route::post('vaults/details/delete', [VaultsController::class, 'details_destroy'])->name('vaults.details.delete');
    Route::post('vaults/{id}/open', [VaultsController::class, 'open'])->name('vaults.open');
    Route::get('vaults/{id}/close', [VaultsController::class, 'close'])->name('vaults.close');
    Route::post('vaults/{id}/close/store', [VaultsController::class, 'close_store'])->name('vaults.close.store');
    Route::get('vaults/{id}/print/details', [VaultsController::class, 'print_vault_details'])->name('vaults.print.vault.details');
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
    Route::get('planillas/pagos/print-group/{id}/{paymentschedule_id?}', [PlanillasController::class, 'planillas_pagos_print_group'])->name('planillas.pagos.print.group');
    Route::get('planillas/pagos/aguinaldos/print/{id}', [PlanillasController::class, 'planillas_pagos_aguinaldos_print']);
    Route::post('planillas/pagos/delete', [PlanillasController::class, 'planillas_pagos_delete'])->name('planillas.pagos.delete');
    Route::get('planillas/pagos/delete/print/{id}', [PlanillasController::class, 'planillas_pagos_delete_print']);

    Route::post('planillas/pagos/centralizada/search', [PlanillasController::class, 'planillas_pagos_centralizada_search'])->name('planillas.pagos.centralizada.search');
    Route::get('planillas/pagos/people/search', [PlanillasController::class, 'planillas_pagos_people_search']);

    // Planillas de pagos
    Route::resource('paymentschedules', PaymentschedulesController::class);
    Route::get('paymentschedules/ajax/list', [PaymentschedulesController::class, 'list']);
    Route::post('paymentschedules/update/status', [PaymentschedulesController::class, 'update_status'])->name('paymentschedules.update.status');
    Route::post('paymentschedules/update/centralize', [PaymentschedulesController::class, 'update_centralize'])->name('paymentschedules.update.centralize');
    Route::post('paymentschedules/cancel', [PaymentschedulesController::class, 'cancel'])->name('paymentschedules.cancel');
    Route::post('paymentschedules/generate', [PaymentschedulesController::class, 'generate'])->name('paymentschedules.generate');
    Route::get('paymentschedules-files', [PaymentschedulesController::class, 'files_index'])->name('paymentschedules-files.index');
    Route::get('paymentschedules-files/list/{search?}', [PaymentschedulesController::class, 'files_list'])->name('paymentschedules-files.list');
    Route::get('paymentschedules-files/create', [PaymentschedulesController::class, 'files_create'])->name('paymentschedules-files.create');
    Route::post('paymentschedules-files/generate', [PaymentschedulesController::class, 'files_generate'])->name('paymentschedules-files.generate');
    Route::post('paymentschedules-files/store', [PaymentschedulesController::class, 'files_store'])->name('paymentschedules-files.store');
    Route::post('paymentschedules-files/delete', [PaymentschedulesController::class, 'files_delete'])->name('paymentschedules-files.delete');

    // Activos fijos
    Route::resource('assets', AssetsControllers::class);
    Route::get('assets/list/ajax', [AssetsControllers::class, 'list'])->name('assets.list');
    Route::post('assets/image/delete', [AssetsControllers::class, 'image_delete'])->name('assets.image.delete');
    Route::get('assets/search/ajax', [AssetsControllers::class, 'search']);
    Route::post('assets/maintenances/store', [AssetsControllers::class, 'maintenances_store'])->name('maintenances.store');
    Route::post('assets/maintenances/report/store', [AssetsControllers::class, 'maintenances_report_store'])->name('maintenances.report.store');
    Route::get('assets/maintenances/{id}/print/{type?}', [AssetsControllers::class, 'maintenances_print'])->name('maintenances.print');
    // Para validar si el PB no está ocupado
    Route::get('assets/search/code', [AssetsControllers::class, 'search_by_code']);

    // Aguinaldo
    Route::get('bonuses', [PaymentschedulesController::class, 'bonuses_index'])->name('bonuses.index');
    Route::get('bonuses/create', [PaymentschedulesController::class, 'bonuses_create'])->name('bonuses.create');
    Route::post('bonuses/generate', [PaymentschedulesController::class, 'bonuses_generate'])->name('bonuses.generate');
    Route::post('bonuses/store', [PaymentschedulesController::class, 'bonuses_store'])->name('bonuses.store');
    Route::get('bonuses/{id}', [PaymentschedulesController::class, 'bonuses_show'])->name('bonuses.show');
    Route::put('bonuses/{id}', [PaymentschedulesController::class, 'bonuses_update'])->name('bonuses.update');
    Route::post('bonuses/{id}/print', [PaymentschedulesController::class, 'bonuses_print'])->name('bonuses.print');
    Route::delete('bonuses/{id}', [PaymentschedulesController::class, 'bonuses_delete'])->name('bonuses.destroy');
    Route::get('bonuses/{id}/recipe/{detail_id?}', [PaymentschedulesController::class, 'bonuses_recipes'])->name('bonuses.recipe');

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
    Route::get('social-security/payments/list/{search?}', [SocialSecurityController::class, 'payments_list'])->name('payments.list');
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
    Route::post('check/aprobar', [CheckController::class, 'aprobar_checks'])->name('checks.aprobar');
    Route::post('check/entregar', [CheckController::class, 'entregar_checks'])->name('checks.entregar');
    Route::post('check/habilitar', [CheckController::class, 'habilitar_checks'])->name('checks.habilitar');
    Route::post('check/update', [CheckController::class, 'update_checks'])->name('checks.updat');
    Route::post('check/devolver', [CheckController::class, 'devolver_checks'])->name('checks.devolver');
    Route::delete('check/delete', [CheckController::class, 'destroy'])->name('checks.delet');

    // Periods
    Route::get('periods/tipo_direccion_adminstrativa/{id}', [PeriodsController::class, 'periods_tipo_direccion_adminstrativa']);

    Route::get('reports/check/check-browse', [CheckController::class, 'report_view'])->name('report.check.browse');
    Route::post('reports/check/chek-list', [ReportsController::class, 'check_list'])->name('reports.check.list');

    // Administrativo

    // *Contratos
    Route::resource('contracts', ContractsController::class);
    Route::get('contracts/ajax/list', [ContractsController::class, 'list']);
    Route::post('contracts/status', [ContractsController::class, 'contracts_status'])->name('contracts.status');
    Route::post('contracts/resolution/update', [ContractsController::class, 'contracts_resolution_update'])->name('contracts.resolution.update');
    Route::post('contracts/ratificate', [ContractsController::class, 'contracts_ratificate'])->name('contracts.ratificate');
    Route::post('contracts/addendum/store', [ContractsController::class, 'contracts_addendum_store'])->name('contracts.addendum.store');
    Route::post('contracts/addendum/status', [ContractsController::class, 'contracts_addendum_status'])->name('contracts.addendum.status');
    Route::post('contracts/addendum/update', [ContractsController::class, 'contracts_addendum_update'])->name('contracts.addendum.update');
    Route::post('contracts/addendum/delete', [ContractsController::class, 'contracts_addendum_delete'])->name('contracts.addendum.delete');
    Route::post('contracts/transfer/store', [ContractsController::class, 'contracts_transfer_store'])->name('contracts.transfer.store');
    Route::post('contracts/promotion/store', [ContractsController::class, 'contracts_promotion_store'])->name('contracts.promotion.store');
    Route::post('contracts/reassignment/store', [ContractsController::class, 'contracts_reassignment_store'])->name('contracts.reassignment.store');
    Route::get('contracts/direccion-administrativa/{id}', [ContractsController::class, 'contracts_direccion_administrativa']);
    Route::get('contracts/{id}/print/{document}', [ContractsController::class, 'print'])->name('contracts.print');
    Route::post('contracts/{id}/file/store', [ContractsController::class, 'file_store'])->name('contracts.file.store');
    Route::post('contracts/additional/discount/store', [ContractsController::class, 'contracts_additional_discount_store'])->name('contracts.additional.discount.store');
    Route::delete('contracts/additional/discount/{id}/delete', [ContractsController::class, 'contracts_additional_discount_delete'])->name('contracts.additional.discount.delete');
    Route::delete('contracts/file/{id}/destroy', [ContractsController::class, 'file_destroy'])->name('contracts.file.destroy');
    Route::delete('contracts/finished/{id}/destroy', [ContractsController::class, 'finished_destroy'])->name('contracts.finished.destroy');
    Route::get('contracts/search/ajax', [ContractsController::class, 'search']);

    // Programs
    Route::post('programs/store', [ProgramsController::class, 'store'])->name('programs.store');
    Route::put('programs/{id}/update', [ProgramsController::class, 'update'])->name('programs.update');
    Route::post('programs/restrict', [ProgramsController::class, 'restrict'])->name('programs.restrict');
    Route::get('programs/ajax/list', [ProgramsController::class, 'list']);
    
    // Bonos antigüedad
    Route::get('seniority-bonus-people', [SeniorityBonusPeopleController::class, 'index'])->name('voyager.seniority-bonus-people.index');

    // Finanzas

    // * Personas externas
    Route::get('person-externals/create', [FinancesController::class, 'person_external_create'])->name('voyager.person-externals.create');
    Route::post('person-externals/store', [FinancesController::class, 'person_external_store'])->name('voyager.person-externals.store');

    // * Memo types
    Route::get('memos-types/create', [FinancesController::class, 'memos_types_create'])->name('voyager.memos-types.create');
    Route::post('memos-types/store', [FinancesController::class, 'memos_types_store'])->name('voyager.memos-types.store');
    Route::get('memos-types/{id}', [FinancesController::class, 'memos_types_show'])->name('voyager.memos-types.show');
    Route::get('memos-types/{id}/edit', [FinancesController::class, 'memos_types_edit'])->name('voyager.memos-types.edit');
    Route::post('memos-types/{id}/update', [FinancesController::class, 'memos_types_update'])->name('voyager.memos-types.update');

    // * Memos
    Route::resource('memos', MemosController::class);
    Route::get('memos/ajax/list', [MemosController::class, 'list']);
    Route::get('memos/{id}/print', [MemosController::class, 'print']);

    // Biométrico
    Route::get('schedules/create', [SchedulesController::class, 'create'])->name('voyager.schedules.create');
    Route::post('schedules/store', [SchedulesController::class, 'store'])->name('schedules.store');
    Route::get('schedules/{id}/edit', [SchedulesController::class, 'edit'])->name('voyager.schedules.edit');
    Route::put('schedules/{id}', [SchedulesController::class, 'update'])->name('schedules.update');
    Route::get('schedules/{id}/assignments', [SchedulesController::class, 'assignments_index'])->name('schedules.assignments');
    Route::get('schedules/{id}/assignments/create', [SchedulesController::class, 'assignments_create'])->name('schedules.assignments.create');
    Route::post('schedules/{id}/assignments/store', [SchedulesController::class, 'assignments_store'])->name('schedules.assignments.store');
    Route::post('schedules/assignments/update', [SchedulesController::class, 'assignments_update'])->name('schedules.assignments.update');
    Route::delete('schedules/assignments/{id}/delete', [SchedulesController::class, 'assignments_delete'])->name('schedules.assignments.delete');

    Route::resource('attendances', AttendancesController::class);
    Route::post('attendances/generate', [AttendancesController::class, 'generate'])->name('attendances.generate');

    Route::post('attendances/absences', [AttendancesController::class, 'absences_store'])->name('attendances.absences.store');

    Route::resource('attendances-permits', AttendancePermitsController::class);
    Route::get('attendances-permits/ajax/list', [AttendancePermitsController::class, 'list'])->name('attendances-permits.list');
    Route::post('attendances-permits/store/personal', [AttendancePermitsController::class, 'store_personal'])->name('attendances-permits.store.personal');

    // Reportes

    // *Recursos humanos
    Route::get('reports/humans-resources/people', [ReportsController::class, 'humans_resources_people_index'])->name('reports.humans_resources.people.index');
    Route::post('reports/humans-resources/people/list', [ReportsController::class, 'humans_resources_people_list'])->name('reports.humans_resources.people.list');
    Route::get('reports/humans-resources/contraloria', [ReportsController::class, 'humans_resources_contraloria_index'])->name('reports.humans_resources.contraloria');
    Route::post('reports/humans-resources/contraloria/list', [ReportsController::class, 'humans_resources_contraloria_list'])->name('reports.humans_resources.contraloria.list');
    Route::get('reports/humans-resources/aniversarios', [ReportsController::class, 'humans_resources_aniversarios_index'])->name('reports.humans_resources.aniversarios');
    Route::post('reports/humans-resources/aniversarios/list', [ReportsController::class, 'humans_resources_aniversarios_list'])->name('reports.humans_resources.aniversarios.list');
    Route::get('reports/humans-resources/jobs', [ReportsController::class, 'humans_resources_jobs_index'])->name('reports.humans_resources.jobs');
    Route::post('reports/humans-resources/jobs/list', [ReportsController::class, 'humans_resources_jobs_list'])->name('reports.humans_resources.jobs.list');
    Route::get('reports/humans-resources/relationships', [ReportsController::class, 'humans_resources_relationships_index'])->name('reports.humans_resources.relationships');
    Route::post('reports/humans-resources/relationships/list', [ReportsController::class, 'humans_resources_relationships_list'])->name('reports.humans_resources.relationships.list');
    Route::get('reports/humans-resources/projects/details', [ReportsController::class, 'contracts_projects_details_index'])->name('reports.humans_resources.projects.details.index');
    Route::post('reports/humans-resources/projects/details', [ReportsController::class, 'contracts_projects_details_list'])->name('reports.humans_resources.projects.details.list');
    Route::get('reports/humans-resources/bonus', [ReportsController::class, 'bonus_index'])->name('reports.humans_resources.bonus.index');
    Route::post('reports/humans-resources/bonus/list', [ReportsController::class, 'bonus_list'])->name('reports.humans_resources.bonus.list');
    Route::get('reports/humans-resources/attendances', [ReportsController::class, 'attendances_index'])->name('reports.humans_resources.attendances.index');

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
    Route::get('reports/social-security/exports', [ReportsController::class, 'social_security_exports_index'])->name('reports.social_security.exports.index');
    Route::post('reports/social-security/exports/list', [ReportsController::class, 'social_security_exports_list'])->name('reports.social_security.exports.list');
    Route::get('reports/social-security/payrollpayments', [ReportsController::class, 'social_security_payrollpayments_index'])->name('reports.social_security.payrollpayments.index');
    Route::post('reports/social-security/payrollpayments/list', [ReportsController::class, 'social_security_payrollpayments_list'])->name('reports.social_security.payrollpayments.list');

    // Cashier
    Route::get('reports/cashier/cashiers', [ReportsController::class, 'cashier_cashiers_index'])->name('reports.cashier.cashiers.index');
    Route::post('reports/cashier/cashiers/list', [ReportsController::class, 'cashier_cashiers_list'])->name('reports.cashier.cashiers.list');
    Route::get('reports/cashier/payments', [ReportsController::class, 'cashier_payments_index'])->name('reports.cashier.payments.index');
    Route::post('reports/cashier/payments/list', [ReportsController::class, 'cashier_payments_list'])->name('reports.cashier.payments.list');
    Route::get('reports/cashier/vaults', [ReportsController::class, 'cashier_vaults_index'])->name('reports.cashier.vaults.index');
    Route::post('reports/cashier/vaults/list', [ReportsController::class, 'cashier_vaults_list'])->name('reports.cashier.vaults.list');

    // Contrataciones
    Route::get('reports/management/contracts', [ReportsController::class, 'management_contracts_index'])->name('reports.management.contracts.index');
    Route::post('reports/management/contracts/list', [ReportsController::class, 'management_contracts_list'])->name('reports.management.contracts.list');
    Route::get('reports/management/addendums', [ReportsController::class, 'management_addendums_index'])->name('reports.management.addendums.index');
    Route::post('reports/management/addendums/list', [ReportsController::class, 'management_addendums_list'])->name('reports.management.addendums.list');

    // Paymentschedules
    Route::get('reports/paymentschedules/details-status', [ReportsController::class, 'paymentschedules_details_status_index'])->name('reports.paymentschedules.details.status.index');
    Route::post('reports/paymentschedules/details-status/list', [ReportsController::class, 'paymentschedules_details_status_list'])->name('reports.paymentschedules.details.status.list');

    // Users
    Route::delete('users/{id}', [UsersController::class, 'destroy'])->name('voyager.users.destroy');
    Route::get('users/{id}/restore', [UsersController::class, 'restore'])->name('voyager.users.restore');

    // Complementos
    Route::get('get_duration/{start}/{finish}', [HomeController::class, 'get_duration'])->name('get.duration');
    Route::get('plugins/cashiers/tickets', [PluginsController::class, 'cashiers_tickets'])->name('cashiers.tickets');
    Route::get('plugins/cashiers/tickets/generate', [PluginsController::class, 'cashiers_tickets_generate'])->name('cashiers.tickets.generate');
    Route::post('plugins/cashiers/tickets/print', [PluginsController::class, 'cashiers_tickets_print'])->name('cashiers.tickets.print');

    Route::post('plugins/cashiers/tickets/set', function(Request $request){
        if($request->reset){
            set_setting('auxiliares.numero_ticket', $request->value);
            return redirect('admin/planillas/pagos')->with(['message' => 'Número de ticket actualizado', 'alert-type' => 'info']);
        }else{
            set_setting('auxiliares.numero_ticket', setting('auxiliares.numero_ticket') +1);
            return response()->json(['ticket' => setting('auxiliares.numero_ticket') +1]);
        }
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

// Redirect development mode
Route::get('/admin/notice', function() {
    return view('errors.notice');
});

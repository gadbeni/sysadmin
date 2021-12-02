<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePayrollPaymentsAltTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_payments', function(Blueprint $table){
            $table->foreignId('spreadsheet_id')->nullable()->constrained('spreadsheets')->after('planilla_haber_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_payments', function (Blueprint $table) {
            $table->dropColumn(['spreadsheet_id']);
        });
    }
}

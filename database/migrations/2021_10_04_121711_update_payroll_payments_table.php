<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePayrollPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_payments', function(Blueprint $table){
            $table->string('payment_id')->nullable()->after('fpc_number');
            $table->decimal('penalty_payment', 10, 2)->nullable()->after('fpc_number');
            $table->string('check_id')->nullable()->after('deposit_number');
            $table->decimal('penalty_check', 10, 2)->nullable()->after('deposit_number');
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
            $table->dropColumn(['payment_id', 'penalty_payment', 'check_id', 'penalty_check']);
        });
    }
}

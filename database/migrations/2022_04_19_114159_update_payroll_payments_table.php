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
            $table->string('recipe_number_afp')->nullable();
            $table->string('check_number_afp')->nullable();
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
            $table->dropColumn(['recipe_number_afp', 'check_number_afp']);
        });
    }
}

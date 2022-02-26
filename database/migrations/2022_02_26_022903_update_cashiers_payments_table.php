<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCashiersPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cashiers_payments', function(Blueprint $table){
            $table->foreignId('paymentschedules_detail_id')->nullable()->constrained('paymentschedules_details');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cashiers_payments', function (Blueprint $table) {
            $table->dropColumn(['paymentschedules_detail_id']);
        });
    }
}

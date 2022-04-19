<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateChecksPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checks_payments', function(Blueprint $table){
            $table->foreignId('paymentschedule_id')->nullable()->constrained('paymentschedules');
            $table->smallInteger('afp')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checks_payments', function (Blueprint $table) {
            $table->dropColumn(['paymentschedule_id', 'afp']);
        });
    }
}

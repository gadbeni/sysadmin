<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashiersPaymentsDeletesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashiers_payments_deletes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashiers_payment_id')->nullable()->constrained('cashiers_payments');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->text('observations')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashiers_payments_deletes');
    }
}

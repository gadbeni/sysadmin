<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts_payments', function (Blueprint $table) {
            $table->id();
            $table->string('memo_number')->nullable();
            $table->string('check_number')->nullable();
            $table->string('amount')->nullable();
            $table->date('date')->nullable();
            $table->string('status')->nullable()->default('en proceso');
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
        Schema::dropIfExists('contracts_payments');
    }
}

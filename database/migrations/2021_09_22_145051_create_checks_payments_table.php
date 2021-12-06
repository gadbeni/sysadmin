<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecksPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checks_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('checks_beneficiary_id')->nullable()->constrained('checks_beneficiaries');
            $table->integer('planilla_haber_id')->nullable();
            $table->text('number')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->date('date_print')->nullable();
            $table->text('observations')->nullable();
            $table->smallInteger('status')->nullable()->default(1);
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
        Schema::dropIfExists('checks_payments');
    }
}

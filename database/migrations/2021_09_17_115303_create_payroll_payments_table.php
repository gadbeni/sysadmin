<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->integer('planilla_haber_id')->nullable();
            $table->foreignId('spreadsheet_id')->nullable()->constrained('spreadsheets');
            $table->date('date_payment_afp')->nullable();
            $table->text('fpc_number')->nullable();
            $table->string('payment_id')->nullable();
            $table->decimal('penalty_payment', 10, 2)->nullable();
            $table->string('check_id')->nullable();
            $table->decimal('penalty_check', 10, 2)->nullable();
            $table->date('date_payment_cc')->nullable();
            $table->text('gtc_number')->nullable();
            $table->text('recipe_number')->nullable();
            $table->text('deposit_number')->nullable();
            $table->smallInteger('manual')->nullable();
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
        Schema::dropIfExists('payroll_payments');
    }
}

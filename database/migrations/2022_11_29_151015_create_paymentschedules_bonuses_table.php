<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentschedulesBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentschedules_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('contract_id')->nullable()->constrained('contracts');
            $table->foreignId('direccion_id')->nullable()->constrained('direcciones');
            $table->foreignId('procedure_type_id')->nullable()->constrained('procedure_types');
            $table->smallInteger('year')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->smallInteger('status')->nullable()->default(1);
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
        Schema::dropIfExists('paymentschedules_bonuses');
    }
}

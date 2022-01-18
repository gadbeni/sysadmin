<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashiersPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashiers_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashier_id')->nullable()->constrained('cashiers');
            // Hace referencia a planillahaberes.id
            $table->foreignId('aguinaldo_id')->nullable()->constrained('aguinaldos');
            $table->integer('planilla_haber_id')->nullable();
            // Hace referencia a planillahaberes.Liquido_Pagable
            $table->decimal('amount', 10, 2)->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('cashiers_payments');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentschedulesFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentschedules_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->integer('direccion_administrativa_id')->nullable();
            $table->foreignId('period_id')->nullable()->constrained('periods');
            $table->foreignId('procedure_type_id')->nullable()->constrained('procedure_types');
            $table->string('type')->nullable();
            $table->text('observations')->nullable();
            $table->string('url')->nullable();
            $table->string('status')->nullable()->default('borrador');
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
        Schema::dropIfExists('paymentschedules_files');
    }
}

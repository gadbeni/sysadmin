<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAguinaldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aguinaldos', function (Blueprint $table) {
            $table->id();
            $table->integer('item')->nullable();
            $table->text('funcionario')->nullable();
            $table->string('ci')->nullable();
            $table->integer('nro_dias')->nullable();
            $table->decimal('mes1', 10, 2)->nullable();
            $table->decimal('mes2', 10, 2)->nullable();
            $table->decimal('mes3', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->decimal('sueldo_promedio', 10, 2)->nullable();
            $table->decimal('liquido_pagable', 10, 2)->nullable();
            $table->string('estado')->nullable()->default('pendiente');
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
        Schema::dropIfExists('aguinaldos');
    }
}

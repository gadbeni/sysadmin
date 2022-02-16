<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStipendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stipends', function (Blueprint $table) {
            $table->id();
            $table->string('funcionario');
            $table->string('ci');
            $table->string('cargo');
            $table->decimal('sueldo',8,2);
            $table->integer('dia');
            $table->decimal('montofactura',8,2);
            $table->decimal('rciva',8,2);
            $table->decimal('total',8,2);
            $table->decimal('liqpagable',8,2);
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
        Schema::dropIfExists('stipends');
    }
}

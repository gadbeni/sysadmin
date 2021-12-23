<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpreadsheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spreadsheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->integer('direccion_administrativa_id')->nullable();
            $table->integer('tipo_planilla_id')->nullable();
            $table->string('codigo_planilla')->nullable();
            $table->string('year')->nullable();
            $table->string('month')->nullable();
            $table->integer('people')->nullable();
            $table->string('afp_id')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->decimal('total_afp', 10, 2)->nullable();
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
        Schema::dropIfExists('spreadsheets');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->integer('direccion_administrativa_id')->nullable();
            $table->foreignId('unidad_administrativa_id')->nullable()->constrained('unidades');
            $table->integer('procedure_type_id')->nullable()->constrained('procedure_types');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('class')->nullable();
            $table->string('number')->nullable();
            $table->string('programatic_category')->nullable();
            $table->string('amount')->nullable();
            $table->smallInteger('year')->nullable();
            $table->date('start')->nullable();
            $table->date('finish')->nullable();
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
        Schema::dropIfExists('programs');
    }
}

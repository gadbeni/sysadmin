<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('item')->nullable();
            $table->smallInteger('level')->nullable();
            $table->string('name')->nullable();
            $table->string('dependence')->nullable();
            $table->integer('direccion_administrativa_id')->nullable();
            $table->foreignId('unidad_administrativa_id')->nullable()->constrained('direcciones');
            $table->decimal('salary', 10, 2)->nullable();
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
        Schema::dropIfExists('jobs');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->integer('direccion_administrativa_id')->nullable();
            $table->string('name')->nullable();
            $table->string('ci')->nullable();
            $table->string('job')->nullable();
            $table->string('designation')->nullable();
            $table->date('designation_date')->nullable();
            $table->string('job_alt')->nullable();
            $table->string('designation_alt')->nullable();
            $table->date('designation_date_alt')->nullable();
            $table->string('status')->nullable()->default(1);
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
        Schema::dropIfExists('signatures');
    }
}

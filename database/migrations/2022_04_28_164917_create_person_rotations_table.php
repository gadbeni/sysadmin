<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonRotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_rotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destiny_id')->nullable()->constrained('people');
            $table->string('destiny_job')->nullable();
            $table->foreignId('responsible_id')->nullable()->constrained('people');
            $table->string('responsible_job')->nullable();
            $table->string('destiny_dependency')->nullable();
            $table->foreignId('contract_id')->nullable()->constrained('contracts');
            $table->foreignId('office_id')->nullable()->constrained('offices');
            $table->date('date')->nullable();
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
        Schema::dropIfExists('person_rotations');
    }
}

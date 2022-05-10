<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonIrremovabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_irremovabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->nullable()->constrained('people');
            $table->foreignId('irremovability_type_id')->nullable()->constrained('irremovability_types');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->text('observations')->nullable();
            $table->date('start')->nullable();
            $table->date('finish')->nullable();
            $table->string('file')->nullable();
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
        Schema::dropIfExists('person_irremovabilities');
    }
}

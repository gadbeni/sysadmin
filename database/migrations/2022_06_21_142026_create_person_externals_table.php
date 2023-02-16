<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonExternalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_externals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_external_type_id')->nullable()->constrained('person_external_types');
            $table->foreignId('person_id')->nullable()->constrained('people');
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->string('full_name')->nullable();
            $table->string('ci_nit')->unique();
            $table->string('number_acount')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->string('job')->nullable();
            $table->smallInteger('family')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->text('observations')->nullable();
            $table->text('docs')->nullable();
            $table->text('location')->nullable();
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
        Schema::dropIfExists('person_externals');
    }
}

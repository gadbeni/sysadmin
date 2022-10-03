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
            $table->string('full_name')->nullable();
            $table->string('nit')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('person_externals');
    }
}

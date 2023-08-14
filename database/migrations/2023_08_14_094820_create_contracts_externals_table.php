<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsExternalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts_externals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('person_id')->nullable()->constrained('people');
            $table->foreignId('direccion_id')->nullable()->constrained('direcciones');
            $table->foreignId('procedure_type_id')->nullable()->constrained('procedure_types');
            $table->date('start')->nullable();
            $table->date('finish')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('job_location')->nullable();
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
        Schema::dropIfExists('contracts_externals');
    }
}

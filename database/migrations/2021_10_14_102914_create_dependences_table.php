<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDependencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dependences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('name')->nullable();
            $table->string('nit')->nullable();
            $table->string('code')->nullable();
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('legal_representative_name')->nullable();
            $table->string('legal_representative_dni')->nullable();
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
        Schema::dropIfExists('dependences');
    }
}

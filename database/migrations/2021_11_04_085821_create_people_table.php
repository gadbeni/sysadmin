<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('ci')->unique();
            $table->string('profession')->nullable();
            $table->string('issued')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->smallInteger('afp')->nullable();
            $table->smallInteger('afp_status')->nullable()->default(1);
            $table->string('gender')->nullable();
            $table->date('birthday')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('nua_cua')->nullable();
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
        Schema::dropIfExists('people');
    }
}

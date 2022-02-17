<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeniorityBonusPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seniority_bonus_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seniority_bonus_type_id')->nullable()->constrained('seniority_bonus_types');
            $table->foreignId('person_id')->nullable()->constrained('people');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->smallInteger('years')->nullable();
            $table->smallInteger('months')->nullable();
            $table->smallInteger('days')->nullable();
            $table->smallInteger('quantity')->nullable();
            $table->date('start')->nullable();
            $table->text('observations')->nullable();
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
        Schema::dropIfExists('seniority_bonus_people');
    }
}

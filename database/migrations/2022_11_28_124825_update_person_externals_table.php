<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePersonExternalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('person_externals', function(Blueprint $table){
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->date('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->string('job')->nullable();
            $table->smallInteger('family')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('person_externals', function (Blueprint $table) {
            $table->dropColumn(['city_id', 'birthday', 'gender', 'job', 'family']);
        });
    }
}

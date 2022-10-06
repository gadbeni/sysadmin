<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddendumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addendums', function(Blueprint $table){
            $table->foreignId('signature_id')->nullable()->constrained('signatures');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addendums', function (Blueprint $table) {
            $table->dropColumn(['signature_id']);
        });
    }
}

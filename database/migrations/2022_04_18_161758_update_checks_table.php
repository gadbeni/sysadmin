<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateChecksTable extends Migration
{
    public function up()
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->string('fentregar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checks', function(Blueprint $table) {
            $table->dropColumn(['fentregar']);
        });
    }
}

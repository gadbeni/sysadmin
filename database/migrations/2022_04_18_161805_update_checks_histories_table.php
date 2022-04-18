<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateChecksHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('checks_histories', function (Blueprint $table) {
            $table->string('fentregar')->nullable();
        });
    }


    public function down()
    {
        Schema::table('checks_histories', function (Blueprint $table) {
            $table->dropColumn(['fentregar']);
        });
    }
}

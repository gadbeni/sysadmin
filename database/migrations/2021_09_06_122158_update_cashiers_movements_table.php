<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCashiersMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cashiers_movements', function(Blueprint $table){
            $table->foreignId('cashier_id_to')->nullable()->constrained('cashiers')->after('cashier_id_from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cashiers_movements', function (Blueprint $table) {
            $table->dropColumn(['cashier_id_to']);
        });
    }
}

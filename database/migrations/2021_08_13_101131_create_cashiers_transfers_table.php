<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashiersTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashiers_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from')->nullable()->constrained('cashiers');
            $table->foreignId('to')->nullable()->constrained('cashiers');
            $table->decimal('amount', 10, 2)->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('cashiers_transfers');
    }
}

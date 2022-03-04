<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentschedulesHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentschedules_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paymentschedule_id')->nullable()->constrained('paymentschedules');
            $table->foreignId('office_id')->nullable()->constrained('offices');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('type')->nullable();
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
        Schema::dropIfExists('paymentschedules_histories');
    }
}

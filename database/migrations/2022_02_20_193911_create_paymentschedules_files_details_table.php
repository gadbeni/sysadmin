<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentschedulesFilesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentschedules_files_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paymentschedules_file_id')->nullable()->constrained('paymentschedules_files');
            $table->foreignId('person_id')->nullable()->constrained('people');
            $table->text('details')->nullable();
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
        Schema::dropIfExists('paymentschedules_files_details');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddendumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addendums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->nullable()->constrained('contracts');
            $table->foreignId('signature_id')->nullable()->constrained('signatures');
            $table->date('start')->nullable();
            $table->date('finish')->nullable();
            $table->text('details_payments')->nullable();
            $table->text('observations')->nullable();
            $table->string('status')->nullable()->default('elaborado');
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
        Schema::dropIfExists('addendums');
    }
}

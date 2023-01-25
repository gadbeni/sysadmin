<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractRatificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_ratifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->nullable()->constrained('contracts');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->date('date')->nullable();
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
        Schema::dropIfExists('contract_ratifications');
    }
}

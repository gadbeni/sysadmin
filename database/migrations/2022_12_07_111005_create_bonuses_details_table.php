<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonuses_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bonus_id')->nullable()->constrained('bonuses');
            $table->foreignId('contract_id')->nullable()->constrained('contracts');
            $table->foreignId('procedure_type_id')->nullable()->constrained('procedure_types');
            $table->decimal('salary_1', 10, 2)->nullable();
            $table->decimal('salary_2', 10, 2)->nullable();
            $table->decimal('salary_3', 10, 2)->nullable();
            $table->smallInteger('days')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->smallInteger('status')->nullable()->default(1);
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
        Schema::dropIfExists('bonuses_details');
    }
}

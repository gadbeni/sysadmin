<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentschedulesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentschedules_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paymentschedule_id')->nullable()->constrained('paymentschedules');
            $table->foreignId('contract_id')->nullable()->constrained('contracts');
            $table->smallInteger('worked_days')->nullable();
            $table->string('job')->nullable();
            $table->smallInteger('job_level')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->decimal('seniority_bonus_percentage', 10, 2)->nullable();
            $table->decimal('solidary', 10, 2)->nullable();
            $table->decimal('common_risk', 10, 2)->nullable();
            $table->decimal('afp_commission', 10, 2)->nullable();
            $table->decimal('retirement', 10, 2)->nullable();
            $table->decimal('solidary_national', 10, 2)->nullable();
            $table->decimal('solidary_employer', 10, 2)->nullable();
            $table->decimal('housing_employer', 10, 2)->nullable();
            $table->decimal('health', 10, 2)->nullable();
            $table->decimal('rc_iva_amount', 10, 2)->nullable();
            $table->decimal('faults_quantity', 10, 2)->nullable();
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
        Schema::dropIfExists('paymentschedules_details');
    }
}

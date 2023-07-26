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
            $table->foreignId('applicant_id')->nullable()->constrained('contracts');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('program_id')->nullable()->constrained('programs');
            $table->string('code')->nullable();
            $table->date('start')->nullable();
            $table->date('finish')->nullable();
            $table->date('signature_date')->nullable();
            $table->date('nci_date')->nullable();
            $table->string('nci_code')->nullable();
            $table->date('certification_date')->nullable();
            $table->string('certification_code')->nullable();
            $table->date('request_date')->nullable();
            $table->date('legal_report_date')->nullable();
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

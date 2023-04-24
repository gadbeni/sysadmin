<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddendumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addendums', function(Blueprint $table){
            $table->foreignId('applicant_id')->nullable()->constrained('contracts');
            $table->date('nci_date')->nullable();
            $table->string('nci_code')->nullable();
            $table->date('certification_date')->nullable();
            $table->string('certification_code')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addendums', function (Blueprint $table) {
            $table->dropColumn(['applicant_id', 'nci_date', 'nci_code', 'certification_date', 'certification_code']);
        });
    }
}

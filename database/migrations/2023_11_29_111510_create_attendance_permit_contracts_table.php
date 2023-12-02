<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancePermitContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_permit_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_permit_id')->nullable()->constrained('attendance_permits');
            $table->foreignId('contract_id')->nullable()->constrained('contracts');
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
        Schema::dropIfExists('attendance_permit_contracts');
    }
}

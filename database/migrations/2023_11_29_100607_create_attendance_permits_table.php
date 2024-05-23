<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancePermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_permits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('attendance_permit_type_id')->nullable()->constrained('attendance_permit_types');
            $table->string('category')->nullable();
            $table->date('date')->nullable();
            $table->date('start')->nullable();
            $table->date('finish')->nullable();
            $table->time('time_start')->nullable();
            $table->time('time_finish')->nullable();
            $table->text('purpose')->nullable();
            $table->text('justification')->nullable();
            $table->string('type_transport')->nullable();
            $table->string('file')->nullable();
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
        Schema::dropIfExists('attendance_permits');
    }
}

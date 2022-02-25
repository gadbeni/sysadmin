<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecksHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checks_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('check_id')->nullable()->constrained('checks');
            $table->foreignId('office_id')->nullable()->constrained('offices');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->text('observacion')->nullable();
            $table->string('status')->nullable()->default('registrado');
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
        Schema::dropIfExists('checks_histories');
    }
}

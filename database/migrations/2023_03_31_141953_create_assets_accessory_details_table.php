<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsAccessoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets_accessory_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assets_id')->nullable()->constrained('assets');
            $table->foreignId('assets_accessory_id')->nullable()->constrained('assets_accessories');
            $table->string('type')->nullable()->default('por defecto');
            $table->string('value')->nullable();
            $table->text('observations')->nullable();
            $table->smallInteger('status')->nullable()->default(1);
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
        Schema::dropIfExists('assets_accessory_details');
    }
}

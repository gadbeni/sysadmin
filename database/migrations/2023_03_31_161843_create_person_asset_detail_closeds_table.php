<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonAssetDetailClosedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_asset_detail_closeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('person_asset_detail_id')->nullable()->constrained('person_asset_details');
            $table->foreignId('person_asset_destiny_id')->nullable()->constrained('person_assets');
            $table->string('type')->nullable();
            $table->text('observations')->nullable();
            $table->date('date')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('person_asset_detail_closeds');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonAssetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_asset_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_asset_id')->nullable()->constrained('person_assets');
            $table->foreignId('asset_id')->nullable()->constrained('assets');
            $table->foreignId('office_id')->nullable()->constrained('offices');
            $table->text('observations')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_asset_details');
    }
}

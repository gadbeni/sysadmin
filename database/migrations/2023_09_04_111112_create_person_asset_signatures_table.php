<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonAssetSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_asset_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_asset_id')->nullable()->constrained('person_assets');
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
        Schema::dropIfExists('person_asset_signatures');
    }
}

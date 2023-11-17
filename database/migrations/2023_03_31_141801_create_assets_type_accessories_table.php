<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTypeAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets_type_accessories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assets_type_id')->nullable()->constrained('assets_types');
            $table->foreignId('assets_accessory_id')->nullable()->constrained('assets_accessories');
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
        Schema::dropIfExists('assets_type_accessories');
    }
}

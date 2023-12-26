<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetMaintenanceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_maintenance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_maintenance_type_id')->nullable()->constrained('asset_maintenance_types');
            $table->foreignId('asset_maintenance_id')->nullable()->constrained('asset_maintenances');
            $table->text('observations')->nullable();
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
        Schema::dropIfExists('asset_maintenance_details');
    }
}

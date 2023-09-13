<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technical_id')->nullable()->constrained('contracts');
            $table->foreignId('destiny_id')->nullable()->constrained('contracts');
            $table->foreignId('supervisor_id')->nullable()->constrained('contracts');
            $table->foreignId('asset_id')->nullable()->constrained('assets');
            $table->foreignId('direccion_id')->nullable()->constrained('direcciones')->comment('En caso de ser una una de mantenimiento de una DA desconcentrada se registra');
            $table->string('code')->unique();
            $table->date('date')->nullable();
            $table->text('reference')->nullable();
            $table->text('details')->nullable();
            $table->text('observations')->nullable();
            $table->text('images')->nullable();
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
        Schema::dropIfExists('asset_maintenances');
    }
}

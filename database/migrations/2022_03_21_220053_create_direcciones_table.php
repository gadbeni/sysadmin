<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDireccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('direcciones_tipo_id')->nullable()->constrained('direcciones_tipos');
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->foreignId('office_id')->nullable()->constrained('offices');
            $table->string('nombre')->nullable();
            $table->string('sigla')->nullable();
            $table->string('nit')->nullable();
            $table->text('direccion')->nullable();
            $table->smallInteger('orden')->nullable()->default(1000);
            $table->smallInteger('estado')->nullable()->default(1);
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
        Schema::dropIfExists('direccion_administrativas');
    }
}

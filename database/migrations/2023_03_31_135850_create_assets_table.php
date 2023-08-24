<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assets_subcategory_id')->nullable()->constrained('assets_subcategories');
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('code')->unique();
            $table->string('code_siaf')->nullable()->comment('Código asignacdo por el sistema siaf');
            $table->string('code_internal')->nullable()->comment('Número de serie o número de producto');
            $table->string('tags')->nullable()->comment('Palabras claves para ayudar a localizar el activo');
            $table->text('description')->nullable();
            $table->decimal('initial_price', 10, 2)->nullable()->comment('Precio de compra');
            $table->decimal('current_price', 10, 2)->nullable()->comment('Precio actual');
            $table->string('address')->nullable();
            $table->string('location')->nullable();
            $table->text('observations')->nullable();
            $table->text('images')->nullable();
            $table->string('status')->nullable();
            $table->smallInteger('active')->nullable(1);
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
        Schema::dropIfExists('assets');
    }
}

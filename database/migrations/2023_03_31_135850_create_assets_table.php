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
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('code')->unique();
            $table->text('details')->nullable();
            $table->text('images')->nullable();
            $table->date('date_purchase')->nullable();
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemosTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memos_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memos_types_group_id')->nullable()->constrained('memos_types_groups');
            $table->text('name')->nullable();
            $table->text('origin')->nullable();
            $table->text('concept')->nullable();
            $table->text('subject')->nullable();
            $table->string('status')->nullable()->default('activo');
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
        Schema::dropIfExists('memos_types');
    }
}

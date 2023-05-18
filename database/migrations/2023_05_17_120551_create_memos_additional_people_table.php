<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemosAdditionalPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memos_additional_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')->nullable()->constrained('memos');
            $table->foreignId('person_external_id')->nullable()->constrained('person_externals');
            $table->text('observations')->nullable();
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
        Schema::dropIfExists('memos_additional_people');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_id')->nullable()->constrained('people');
            $table->foreignId('destiny_id')->nullable()->constrained('people');
            $table->foreignId('memos_type_id')->nullable()->constrained('memos_types');
            $table->foreignId('person_id')->nullable()->constrained('person_externals');
            $table->foreignId('person_external_id')->nullable()->constrained('person_externals');
            $table->string('order_name')->nullable();
            $table->string('order_job')->nullable();
            $table->string('code')->nullable();
            $table->string('da_sigep')->nullable();
            $table->string('origin')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->text('concept')->nullable();
            $table->text('subject')->nullable();
            $table->text('imputation')->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('memos');
    }
}

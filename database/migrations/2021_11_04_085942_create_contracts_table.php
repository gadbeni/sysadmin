<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->nullable()->constrained('people');
            $table->integer('cargo_id')->nullable();
            $table->foreignId('job_id')->nullable()->constrained('jobs');
            $table->integer('direccion_administrativa_id')->nullable();
            $table->integer('unidad_administrativa_id')->nullable();
            $table->foreignId('program_id')->nullable()->constrained('programs');
            $table->foreignId('procedure_type_id')->nullable()->constrained('procedure_types');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('signature_id')->nullable()->constrained('contracts');
            $table->string('signature_code')->nullable();
            $table->string('code')->nullable();
            $table->text('details_work')->nullable();
            $table->string('preventive_number')->nullable();
            $table->string('organizational_source')->nullable();
            $table->date('start')->nullable();
            $table->date('finish')->nullable();
            $table->date('date_invitation')->nullable();
            $table->date('date_limit_invitation')->nullable();
            $table->date('date_response')->nullable();
            $table->date('date_statement')->nullable();
            $table->date('date_presentation')->nullable();
            $table->date('date_memo')->nullable();
            $table->string('workers_memo')->nullable();
            $table->string('workers_memo_alt')->nullable();
            $table->date('date_memo_res')->nullable();
            $table->date('date_note')->nullable();
            $table->date('date_report')->nullable();
            $table->text('table_report')->nullable();
            $table->text('details_report')->nullable();
            $table->date('date_autorization')->nullable();
            $table->string('certification_poa')->nullable();
            $table->string('certification_pac')->nullable();
            $table->string('status')->nullable()->default('elaborado');
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
        Schema::dropIfExists('contracts');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeyResultTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('key_result_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('comment');
            $table->date('end_date');
            $table->smallInteger('progress');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('keyresult_id');
            $table->index('employee_id');
            $table->index('keyresult_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('keyresult_id')->references('id')->on('key_results');
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
        Schema::dropIfExists('key_result_tasks');
    }
}

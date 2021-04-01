<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeyResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('key_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('comment');
            $table->date('end_date');
            $table->smallInteger('progress');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('okr_id');
            $table->index('employee_id');
            $table->index('okr_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('okr_id')->references('id')->on('okrs');
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
        Schema::dropIfExists('key_results');
    }
}

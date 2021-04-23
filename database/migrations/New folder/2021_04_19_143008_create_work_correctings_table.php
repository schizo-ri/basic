<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkCorrectingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_correctings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id');
            $table->time('time');
            $table->string('comment',1000);
            $table->timestamps();
            $table->index('employee_id');
            $table->index('user_id');
            $table->index('project_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('user_id')->references('id')->on('employees');
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_correctings');
    }
}

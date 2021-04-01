<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetenceDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competence_departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('competence_id');
            $table->unsignedBigInteger('work_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->timestamps();
            $table->index(['competence_id', 'work_id', 'department_id']);
            $table->foreign('competence_id')->references('id')->on('competences');
            $table->foreign('work_id')->references('id')->on('works');
            $table->foreign('department_id')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competence_departments');
    }
}

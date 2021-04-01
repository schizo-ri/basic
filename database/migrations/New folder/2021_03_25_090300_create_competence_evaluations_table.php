<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetenceEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competence_evaluations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('Djelatnik koji ocjenjuje');
            $table->unsignedBigInteger('employee_id')->comment('Ocjenjeni djelatnik');
            $table->date('evaluation_date');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('rating_id');
            $table->timestamps();
            $table->index('user_id');
            $table->index('employee_id');
            $table->index('question_id');
            $table->index('rating_id');
            $table->foreign('user_id')->references('id')->on('employees');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('question_id')->references('id')->on('competence_questions');
            $table->foreign('rating_id')->references('id')->on('competence_ratings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competence_evaluations');
    }
}

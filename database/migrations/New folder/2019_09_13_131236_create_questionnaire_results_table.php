<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('employee_id')->comment('zaposlenik koji ocjenjuje');
            $table->integer('questionnaire_id');
            $table->integer('question_id');
            $table->integer('answer_id')->nullable();
            $table->string('answer')->nullable();
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
        Schema::dropIfExists('questionnaire_results');
    }
}

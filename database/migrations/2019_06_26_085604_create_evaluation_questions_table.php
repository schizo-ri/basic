<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('category_id');
			$table->string('name_question',255);
			$table->string('type',3);
			$table->text('description');
			$table->text('description2');
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
        Schema::dropIfExists('evaluation_questions');
    }
}

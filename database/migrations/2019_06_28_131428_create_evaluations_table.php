<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('user_id')->comment('djelatnik koji ocjenjuje');
			$table->integer('employee_id')->comment('djelatnik koji je ocjenjen');
			$table->date('date');
			$table->integer('questionnaire_id');
			$table->integer('category_id');
			$table->integer('question_id');
			$table->double('koef', 8, 2);
			$table->integer('rating');
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
        Schema::dropIfExists('evaluations');
    }
}

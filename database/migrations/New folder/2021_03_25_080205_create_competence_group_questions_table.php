<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetenceGroupQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competence_group_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('competence_id');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->double('coefficient')->nullable();
            $table->timestamps();
            $table->index('competence_id');
            $table->foreign('competence_id')->references('id')->on('competences');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competence_group_questions');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeyResultsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('key_results_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('key_results_id');
            $table->string('comment');
            $table->index('key_results_id');
            $table->foreign('key_results_id')->references('id')->on('key_results');
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
        Schema::dropIfExists('key_results_comments');
    }
}

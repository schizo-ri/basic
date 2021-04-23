<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeyResultTasksCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('key_result_tasks_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('key_result_tasks_id');
            $table->string('comment');
            $table->index('key_result_tasks_id');
            $table->foreign('key_result_tasks_id')->references('id')->on('key_result_tasks');
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
        Schema::dropIfExists('key_result_tasks_comments');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesigningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('project_no');
            $table->string('name');
            $table->date('date');
            $table->unsignedBigInteger('manager_id');
            $table->unsignedBigInteger('designer_id');
            $table->foreign('manager_id')->references('id')->on('users');
            $table->foreign('designer_id')->references('id')->on('users');
            $table->string('comment');
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
        Schema::dropIfExists('designings');
    }
}

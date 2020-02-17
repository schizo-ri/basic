<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('project_no',10)->comment('broj projekta');
            $table->string('name',191);
            $table->integer('duration')->comment('procjenjeno trajanje u h');
            $table->smallInteger('day_hours')->comment('sati rada u danu');
            $table->smallInteger('saturday')->comment('rad subotom');
            $table->date('start_date')->comment('planirani početak radova');
            $table->date('end_date')->comment('planirani početak radova');
            $table->string('categories',50)->nullable();
            $table->integer('active')->value(1);
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
        Schema::dropIfExists('projects');
    }
}

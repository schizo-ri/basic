<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreparationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preparations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('project_no',10);
            $table->string('name',100);
            $table->date('delivery')->nullable();
            $table->integer('project_manager')->nullable();
            $table->integer('designed_by')->nullable();
            $table->string('preparation',255)->nullable();
            $table->string('mechanical_processing',255)->nullable();
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
        Schema::dropIfExists('preparations');
    }
}

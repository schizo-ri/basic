<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('construction_site_id');
            $table->unsignedBigInteger('competence_id');
            $table->index('employee_id');
            $table->index('construction_site_id');
            $table->index('competence_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('construction_site_id')->references('id')->on('construction_sites');
            $table->foreign('competence_id')->references('id')->on('competences');
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
        Schema::dropIfExists('categorizations');
    }
}

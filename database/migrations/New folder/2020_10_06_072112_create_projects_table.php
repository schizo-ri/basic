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
            $table->string('erp_id',20)->nullable();
            $table->string('customer_oib',20)->nullable();
            $table->integer('investitor_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('name',20);
            $table->string('object',20)->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('active');
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

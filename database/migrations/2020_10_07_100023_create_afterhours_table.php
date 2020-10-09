<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAfterhoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afterhours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('employee_id');
            $table->bigInteger('project_id');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('comment');
            $table->integer('approve');
            $table->bigInteger('approved_id');
            $table->date('approved_date');
            $table->time('approve_h');
            $table->integer('paid');
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
        Schema::dropIfExists('afterhours');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_interviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('first_name',20);
            $table->string('last_name',20);
            $table->string('oib',20);
            $table->string('email',50);
            $table->string('phone',50);
            $table->string('language',50);
            $table->string('title',150);
            $table->string('qualifications',20);
            $table->integer('work_id');
            $table->string('years_service',10);
            $table->double('salary');
            $table->text('comment');
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
        Schema::dropIfExists('job_interviews');
    }
}

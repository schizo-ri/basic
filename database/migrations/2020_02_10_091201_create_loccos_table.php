<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoccosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loccos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('car_id');
            $table->integer('employee_id');
            $table->date('date');
            $table->string('destination');           
            $table->bigInteger('start_km');
            $table->bigInteger('end_km');
            $table->integer('distance');
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('loccos');
    }
}

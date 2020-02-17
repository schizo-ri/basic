<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('manufacturer',50);
            $table->string('model',50);
            $table->string('registration',20);
            $table->string('chassis',30);
            $table->date('first_registration');
            $table->date('last_registration');
            $table->bigInteger('current_km');
            $table->integer('department_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->date('last_service')->nullable();
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
        Schema::dropIfExists('cars');
    }
}

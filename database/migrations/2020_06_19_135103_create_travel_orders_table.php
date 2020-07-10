<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTravelOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->integer('employee_id');
            $table->integer('car_id');
            $table->integer('locco_id')->nullable();
            $table->string('destination', 255);
            $table->string('description', 255)->nullable();
            $table->integer('days');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('advance', 255)->nullable();
            $table->date('advance_date')->nullable();
            $table->string('rest_payout', 255)->nullable();
            $table->integer('calculate_employee')->nullable();
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
        Schema::dropIfExists('travel_orders');
    }
}

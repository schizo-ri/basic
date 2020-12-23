<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnergyLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('energy_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',50);
            $table->string('address',50);
            $table->string('city',20);
            $table->string('phone',20);
            $table->string('comment',191);
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
        Schema::dropIfExists('energy_locations');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgglomerationStationListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agglomeration_station_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('station_id');
            $table->string('reference', 50);
            $table->string('group', 191);
            $table->text('description');
            $table->double('price');
            $table->integer('quantity');
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
        Schema::dropIfExists('agglomeration_station_lists');
    }
}

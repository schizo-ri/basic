<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('preparation_id');
            $table->string('product_number',50);
            $table->string('name',255);
            $table->string('unit',20)->comment('jedinica mjere');
            $table->double('quantity')->comment('količina');
            $table->double('delivered')->comment('isporučena količina');
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
        Schema::dropIfExists('equipment_lists');
    }
}

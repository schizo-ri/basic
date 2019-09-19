<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emailings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('model')->comment('table_id');
            $table->string('method',10)->comment('slanje maila za određenu akciju');
			$table->string('sent_to_dep')->comment('slanje odjelu')->nullable();
            $table->string('sent_to_empl')->comment('slanje djelatniku')->nullable();
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
        Schema::dropIfExists('emailings');
    }
}
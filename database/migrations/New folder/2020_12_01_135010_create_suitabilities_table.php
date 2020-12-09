<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuitabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suitabilities', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('title',255);
			$table->mediumText('description');
            $table->string('contact',100)->nullable();            
			$table->string('phone',100)->nullable();
			$table->string('email',100)->nullable();
			$table->integer('status')->nullable($value = true);
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
        Schema::dropIfExists('suitabilities');
    }
}

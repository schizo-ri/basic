<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsenceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absence_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',50)->comment('vrsta izostanka');
            $table->string('mark',5)->comment('kratka oznaka');
            $table->integer('min_days',2)->comment('minimalno dana GO')->nullable();
            $table->integer('max_days',2)->comment('maximalno dana GO')->nullable();
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
        Schema::dropIfExists('absence_types');
    }
}

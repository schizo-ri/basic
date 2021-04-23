<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWorkCorrectingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_correctings', function (Blueprint $table) {
            $table->tinyInteger('approve')->nullable();
            $table->unsignedBigInteger('approved_id')->nullable();
            $table->date('approved_date')->nullable();
            $table->time('approve_h')->nullable();
            $table->string('approved_reason')->nullable();
            $table->index('approved_id');
            $table->foreign('approved_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

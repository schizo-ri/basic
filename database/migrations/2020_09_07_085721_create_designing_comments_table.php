<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesigningCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designing_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('designing_id');
            $table->unsignedInteger('user_id');
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
        Schema::dropIfExists('designing_comments');
    }
}

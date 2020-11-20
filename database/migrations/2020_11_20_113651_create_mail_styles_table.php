<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailStylesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_styles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('mail_id');
            $table->string('style_header',191);
            $table->string('style_body',191);
            $table->string('style_footer',191);
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
        Schema::dropIfExists('mail_styles');
    }
}
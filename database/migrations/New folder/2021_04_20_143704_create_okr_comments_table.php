<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOkrCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('okr_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('okr_id');
            $table->string('comment');
            $table->index('okr_id');
            $table->foreign('okr_id')->references('id')->on('okrs');
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
        Schema::dropIfExists('okr_comments');
    }
}
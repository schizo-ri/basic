<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateImprovementRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('improvement_recommendations', function (Blueprint $table) {
            $table->index('employee_id');
            $table->index('mentor');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('mentor')->references('id')->on('employees');
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('employee_id');
			$table->integer('ev_employee_id')->comment('evaluated employee');
			$table->integer('questionnaire_id');
			$table->string('mm_yy',10);
			$table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluation_employees');
    }
}

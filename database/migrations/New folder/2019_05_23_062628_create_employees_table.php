<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('father_name',20)->nullable();
            $table->string('mather_name',20)->nullable();
            $table->string('oib',20);
            $table->string('oi',20);
            $table->date('oi_expiry');
            $table->date('b_day');
            $table->string('b_place',50)->nullable()->comment('mjesto rođenja');
            $table->string('mobile',50)->nullable();
			$table->string('priv_email',50)->nullable();
            $table->string('email',50)->nullable();
            $table->string('priv_mobile',50)->nullable();
            $table->string('prebiv_adresa',50)->nullable();
            $table->string('prebiv_grad',50)->nullable();
            $table->string('borav_adresa',50)->nullable();
            $table->string('borav_grad',50)->nullable();
            $table->string('title',50)->nullable()->comment('zvanje');
            $table->string('qualifications',20)->nullable()->comment('stručna sprema');
            $table->string('marital',10)->comment('bračno stanje');
            $table->integer('work_id')->comment('radno mjesto');
			$table->integer('superior_id')->comment('nadređeni djelatnik');
			$table->date('reg_date')->comment('datum prijave');
			$table->integer('probation')->nullable()->comment('probni rok, broj mjeseci');
			$table->string('years_service',10)->nullable()->comment('godine staža');
			$table->string('termination_service',10)->comment('prekid staža')->nullable();
			$table->string('first_job',10)->comment('prekid staža prije prijave')->nullable();
			$table->date('checkout',10)->comment('datum odjave')->nullable();
            $table->text('comment');
            $table->double('effective_cost', 8, 2)->comment('efektivna nijena sata rada')->nullable();
            $table->double('brutto', 8, 2)->comment('brutto godišnja plaća')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
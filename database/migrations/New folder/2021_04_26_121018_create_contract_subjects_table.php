<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_subjects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('location_id');
            $table->string('name')->comment('Naziv subjekta');
            $table->integer('counter_bw')->comment('Početno stanje brojčanika')->nullable();
            $table->integer('counter_c')->comment('Početno stanje brojčanika')->nullable();
            $table->double('flat_rate')->comment('Mjesečni paušal')->nullable();
            $table->double('price_a4_bw')->comment('Cijena otiska')->nullable();
            $table->double('price_a4_c')->comment('Cijena otiska')->nullable();
            $table->integer('no_prints_bw')->comment('Ispisni paket uključen u mjesečni paušal')->nullable();
            $table->integer('no_prints_c')->comment('Ispisni paket uključen u mjesečni paušal')->nullable();
            $table->integer('package_prints_bw')->comment('Uključeno test')->nullable();
            $table->integer('package_prints_c')->comment('Uključeno test')->nullable();
            $table->double('debenture_amount')->comment('Iznos zadužnice')->nullable();
            $table->timestamps();
            $table->index('contract_id');
            $table->index('location_id');
            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->foreign('location_id')->references('id')->on('customer_locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_subjects');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefuelingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refuelings', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('owner_id');
            $table->foreignId('car_id');
            $table->date('date');
            $table->string('FuelType')->nullable();
            $table->string('amount')->nullable();
            $table->string('TotalPrice');
            $table->string('receipt')->unique()->nullable();
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
        Schema::dropIfExists('refuelings');
    }
}

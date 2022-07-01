<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Plns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('plns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nopelanggan')->unique();
            $table->string('kapasitas')->nullable();
            $table->string('kodejaringan')->nullable();
            $table->string('idbgn')->nullable();
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
        Schema::dropIfExists('plns');
    }
}

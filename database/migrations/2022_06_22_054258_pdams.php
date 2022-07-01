<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pdams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('pdams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nopelanggan')->unique();
            $table->string('namapelanggan')->nullable();
            $table->string('sumberpipa')->nullable();
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
         Schema::dropIfExists('pdams');
    }
}

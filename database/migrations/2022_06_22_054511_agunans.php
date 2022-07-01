<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Agunans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agunans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('noagunan')->unique();
            $table->string('besaragunan')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('nib')->nullable();
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
        Schema::dropIfExists('agunans');
    }
}

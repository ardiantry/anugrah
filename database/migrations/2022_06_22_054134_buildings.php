<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Buildings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('idbgn')->nullable();
            $table->string('d_nop')->nullable();
            $table->string('blok')->nullable();
            $table->string('no')->nullable();
            $table->string('njopbgn')->nullable();
            $table->string('luasbgn')->nullable();
            $table->string('njopbgnm2')->nullable();
           $table->geometry('geom');
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
       Schema::dropIfExists('buildings');
    }
}

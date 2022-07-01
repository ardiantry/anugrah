<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JaringanPdams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jaringan_pdams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('handle')->unique();
            $table->string('jenis')->nullable();
            $table->string('keterangan')->nullable();
            $table->lineString('geom', 'GEOMETRY', 32749);
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
       Schema::dropIfExists('jaringan_pdams');
    }
}

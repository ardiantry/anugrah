<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Jalans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('jalans', function (Blueprint $table) {
           $table->bigIncrements('id');
            $table->string('kodeinfra')->unique();
            $table->string('namaruas')->nullable();
            $table->string('fungsi')->nullable();
            $table->string('tahundata')->nullable();
            $table->string('lebarkeras')->nullable();
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
       Schema::dropIfExists('jalans');
    }
}

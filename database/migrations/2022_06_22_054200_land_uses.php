<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LandUses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('land_uses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('idlahan')->unique();
            $table->string('tema')->nullable();
            $table->string('jenis')->nullable();
            $table->string('kegiatan')->nullable();
            $table->string('sumber')->nullable();
            $table->polygon('geom', 'GEOMETRY', 32749);
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
        Schema::dropIfExists('land_uses');
    }
}

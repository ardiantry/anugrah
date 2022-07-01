<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FiscalParcels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiscal_parcels', function (Blueprint $table) {
           $table->bigIncrements('id');
            $table->string('d_nop')->nullable();
            $table->string('d_luas')->nullable();
            $table->string('luas')->nullable();
            $table->string('njop')->nullable();
            $table->string('alamatobj')->nullable();
            $table->string('blok')->nullable();
            $table->string('no')->nullable();
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
        Schema::dropIfExists('fiscal_parcels');
    }
}

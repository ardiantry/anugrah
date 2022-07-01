<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LegalParcels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('legal_parcels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nib')->unique();
            $table->string('tipehak')->nullable();
            $table->string('penggunaanlahan')->nullable();
            $table->string('tataruang')->nullable();
            $table->string('ketinggian')->nullable();
            $table->string('kemiringan')->nullable();
            $table->string('koordinat')->nullable();
            $table->polygon('geom', 'GEOMETRY', 23835);
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
       Schema::dropIfExists('legal_parcels');
    }
}

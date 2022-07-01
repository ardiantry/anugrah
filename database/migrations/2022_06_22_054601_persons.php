<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Persons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('persons', function (Blueprint $table) {
           $table->bigIncrements('id');
            $table->string('idperson')->unique();
            $table->string('namaperson')->nullable();
            $table->string('npwp')->nullable();
            $table->string('nik_induk')->nullable();
            $table->string('alamatperson')->nullable();
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
       Schema::dropIfExists('persons');
    }
}

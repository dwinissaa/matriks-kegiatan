<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisteredmitraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registeredmitra', function (Blueprint $table) {
            $table->string('sobatid')->length(12);
            $table->integer('tahun')->length(4);
            $table->timestamps();

            $table->primary(['sobatid','tahun']);
        });

        Schema::table('registeredmitra', function($table){
            $table->foreign('sobatid')->references('sobatid')->on('mitra')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registeredmitra');
    }
}

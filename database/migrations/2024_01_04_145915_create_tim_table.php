<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tim', function (Blueprint $table) {
            $table->integer('id_tim')->length(2)->primary()->unsigned();
            $table->string('tim')->length(100);
            $table->string('nip_ketim')->length(9);
            $table->timestamps();
        });

        Schema::table('tim', function($table){
            $table->foreign('nip_ketim')->references('nip')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tim');
    }
}

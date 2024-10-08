<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlokasitimTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alokasitim', function (Blueprint $table) {
            $table->integer('id_tim')->length(2)->unsigned();
            $table->string('nip')->length(9);
            $table->timestamps();

            $table->primary(['id_tim','nip']);
        });

        Schema::table('alokasitim', function($table){
            $table->foreign('id_tim')->references('id_tim')->on('tim')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('nip')->references('nip')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alokasitim');
    }
}

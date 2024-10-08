<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->increments('id_keg');
            $table->integer('tahun')->length(4);
            $table->integer('bulan')->length(2);
            $table->string('kegiatan')->length(150);
            $table->integer('subject_meter')->length(2)->unsigned();

            $table->timestamps();
        });

        Schema::table('kegiatan',function($table){
            $table->foreign('subject_meter')->references('id_tim')->on('tim')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kegiatan');
    }
}

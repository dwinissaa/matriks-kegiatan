<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlokasikegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alokasikegiatan', function (Blueprint $table) {
            // $table->increments('id_alokasi')->unsigned();
            $table->integer('id_keg')->unsigned();
            $table->string('id_anggota')->length(12);
            $table->timestamps();

            $table->primary(['id_keg','id_anggota']);
        });

        Schema::table('alokasikegiatan',function($table){
            $table->index('id_keg');
            $table->index('id_anggota');
            $table->foreign('id_keg')->references('id_keg')->on('kegiatan')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alokasikegiatan');
    }
}

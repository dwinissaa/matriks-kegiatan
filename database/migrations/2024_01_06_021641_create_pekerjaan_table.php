<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePekerjaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pekerjaan', function (Blueprint $table) {
            $table->increments('id_pekerjaan')->unsigned();
            $table->integer('id_keg')->unsigned();
            $table->string('id_anggota')->length(12);
            $table->string('uraian_pekerjaan')->length(500);
            $table->integer('target')->length(5);
            $table->string('satuan')->length(25);
            $table->decimal('harga_satuan', $precision = 10, $scale = 2);
            $table->timestamps();
        });

        Schema::table('pekerjaan', function($table){
            // $table->unique(['id_keg', 'email']);
            // $table->index('email');
            $table->foreign('id_keg')->references('id_keg')->on('alokasikegiatan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_anggota')->references('id_anggota')->on('alokasikegiatan')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pekerjaan');
    }
}

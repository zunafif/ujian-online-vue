<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modul', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_praktikum')->unsigned();
            $table->string('nama_modul')->nullable();;
            $table->string('jumlah_bab')->nullable();;
            $table->string('materi')->nullable();;
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent();
        });
        Schema::table('modul', function (Blueprint $table) {
            $table->foreign('id_praktikum')->references('id')->on('praktikum')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modul');
    }
}

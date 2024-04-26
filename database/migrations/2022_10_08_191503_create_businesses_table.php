<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('ruc')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('direccion')->nullable();
            $table->string('codigo_pais')->nullable();
            $table->string('ubigeo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('url_api')->nullable();
            $table->string('email_accounting')->nullable();
            $table->string('urbanizacion')->nullable();
            $table->string('local')->nullable();
            $table->string('nombre_comercial')->nullable();
            $table->string('usuario_sunat')->nullable();
            $table->string('clave_sunat')->nullable();
            $table->string('clave_certificado')->nullable();
            $table->string('certificado')->nullable();
            $table->string('servidor_sunat')->nullable();
            $table->string('pago', 1)->nullable();
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
        Schema::dropIfExists('businesses');
    }
}

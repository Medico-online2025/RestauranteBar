<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSunatUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sunat_users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_comercial');
            $table->string('nombre_usuario');
            $table->string('clave_usuario');
            $table->string('clave_certificado');
            $table->string('ruc');
            $table->string('nombre_certificado');
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
        Schema::dropIfExists('sunat_users');
    }
}

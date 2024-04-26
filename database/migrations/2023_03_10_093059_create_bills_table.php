<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_emision');
            $table->integer('idpurchase_description');
            $table->string('cuenta')->default('Cuenta General');
            $table->decimal('monto', 18, 2);
            $table->string('detalle')->nullable();
            $table->integer('idusuario');
            $table->integer('idcaja');
            $table->integer('idarqueocaja');
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
        Schema::dropIfExists('bills');
    }
}

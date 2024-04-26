<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('idtipo_documento')->nullable();
            $table->integer('idventa')->nullable();
            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->decimal('exonerada', 18, 2);
            $table->decimal('inafecta', 18, 2);
            $table->decimal('gravada', 18, 2);
            $table->decimal('anticipo', 18, 2);
            $table->decimal('igv', 18, 2);
            $table->decimal('gratuita', 18, 2);
            $table->decimal('otros_cargos', 18, 2);
            $table->decimal('total', 18, 2);
            $table->string('observaciones')->nullable();
            $table->integer('idusuario');
            $table->integer('estado')->nullable();
            $table->integer('idmesa')->nullable();
            $table->string('ticket_comanda')->nullable();
            $table->string('ticket_pre_cuenta')->nullable();
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
        Schema::dropIfExists('orders');
    }
}

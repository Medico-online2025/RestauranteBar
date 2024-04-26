<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('idtipo_comprobante');
            $table->integer('idfactura');
            $table->integer('idpago');
            $table->decimal('monto', 16, 2)->nullable();
            $table->integer('idcaja');
            $table->integer('estado')->nullable();
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
        Schema::dropIfExists('detail_payments');
    }
}

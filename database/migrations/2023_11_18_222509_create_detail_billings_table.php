<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_billings', function (Blueprint $table) {
            $table->id();
            $table->integer('idfacturacion');
            $table->integer('idproducto');
            $table->decimal('cantidad', 18, 2);
            $table->decimal('descuento', 18, 2);
            $table->decimal('igv', 18, 2);
            $table->integer('id_afectacion_igv');
            $table->decimal('precio_unitario', 18, 2);
            $table->decimal('precio_total', 18, 2);
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
        Schema::dropIfExists('detail_billings');
    }
}

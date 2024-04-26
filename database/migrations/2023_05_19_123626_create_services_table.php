<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_interno')->nullable();
            $table->string('codigo_barras')->nullable();
            $table->string('codigo_sunat');
            $table->string('descripcion');
            $table->string('marca')->nullable();
            $table->string('presentacion')->nullable();
            $table->integer('idunidad');
            $table->string('idcodigo_igv');
            $table->integer('igv');
            $table->decimal('precio_compra', 18, 2);
            $table->decimal('precio_venta', 18, 2);
            $table->integer('impuesto');
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
        Schema::dropIfExists('services');
    }
}

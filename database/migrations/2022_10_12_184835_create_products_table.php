<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_interno')->nullable();
            $table->string('codigo_barras')->nullable();
            $table->string('codigo_sunat');
            $table->string('descripcion');
            $table->string('marca')->nullable();
            $table->string('presentacion')->nullable();
            $table->integer('idunidad');
            $table->string('idcodigo_igv');
            $table->float('igv');
            $table->decimal('precio_compra', 18, 2);
            $table->decimal('precio_venta', 18, 2);
            $table->integer('impuesto');
            $table->integer('stock')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->integer('opcion')->nullable();
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
        Schema::dropIfExists('products');
    }
}

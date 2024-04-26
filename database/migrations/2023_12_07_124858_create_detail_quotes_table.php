<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_quotes', function (Blueprint $table) {
            $table->id();
            $table->integer('idcotizacion');
            $table->integer('idproducto');
            $table->decimal('cantidad', 18, 2);
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
        Schema::dropIfExists('detail_quotes');
    }
}

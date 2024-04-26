<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGetDetailBfStoredProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS get_detail_bf;");
        DB::unprepared(
            'CREATE PROCEDURE get_detail_bf(
                IN idfactura BIGINT
            )
            BEGIN
                SELECT detail_billings.*, products.descripcion as producto, products.idcodigo_igv as idcodigo_igv, 
                units.id as idunidad, units.codigo as unidad, igv_type_affections.codigo as codigo_igv
                FROM detail_billings 
                INNER JOIN products ON detail_billings.idproducto = products.id
                INNER JOIN units on products.idunidad = units.id
                INNER JOIN igv_type_affections ON products.idcodigo_igv = igv_type_affections.id
                WHERE idfacturacion=idfactura;
            END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('get_detail_bf_stored_procedures');
    }
}

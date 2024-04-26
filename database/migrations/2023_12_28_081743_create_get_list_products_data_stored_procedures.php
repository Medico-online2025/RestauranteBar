<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGetListProductsDataStoredProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS get_list_products_data;");
        DB::unprepared(
            'CREATE PROCEDURE get_list_products_data()
            BEGIN
                SELECT products.*, units.descripcion as unidad, units.codigo as codigo_unidad
                FROM products
                INNER JOIN units ON products.idunidad = units.id
                INNER JOIN igv_type_affections ON products.idcodigo_igv = igv_type_affections.id
                ORDER BY id DESC;
            END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('get_list_products_data_stored_procedures');
    }
}

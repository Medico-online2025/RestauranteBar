<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGetListSaleNotesCashStoredProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS get_list_sale_notes_cash;");
        DB::unprepared(
            'CREATE PROCEDURE get_list_sale_notes_cash(
                IN idcash BIGINT
            )
            BEGIN
                SELECT sale_notes.*, clients.dni_ruc as dni_ruc, clients.nombres as  nombre_cliente 
                FROM sale_notes 
                INNER JOIN clients ON sale_notes.idcliente = clients.id
                WHERE estado = 1 AND idcaja = idcash
                ORDER BY id ASC;
            END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('get_list_sale_notes_cash_stored_procedures');
    }
}

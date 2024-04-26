<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGetListSaleNotesStoredProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS get_list_sale_notes;");
        DB::unprepared(
            'CREATE PROCEDURE get_list_sale_notes()
            BEGIN
                SELECT sale_notes.*, clients.dni_ruc as dni_ruc, clients.nombres as  nombre_cliente 
                FROM sale_notes 
                INNER JOIN clients ON sale_notes.idcliente = clients.id
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
        Schema::dropIfExists('get_list_sale_notes_stored_procedures');
    }
}

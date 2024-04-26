<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGetListBillingsStoredProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS get_list_billings;");
        DB::unprepared(
            'CREATE PROCEDURE get_list_billings()
            BEGIN
                SELECT billings.*, clients.dni_ruc as dni_ruc, clients.nombres as  nombre_cliente 
                FROM billings 
                INNER JOIN clients ON billings.idcliente = clients.id
                WHERE idtipo_comprobante != 6
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
        Schema::dropIfExists('get_list_billings_stored_procedures');
    }
}

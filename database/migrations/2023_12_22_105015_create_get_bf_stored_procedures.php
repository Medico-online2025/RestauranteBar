<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGetBfStoredProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS get_bf;");
        DB::unprepared(
            'CREATE PROCEDURE get_bf(
                IN idfactura BIGINT
            )
            BEGIN
                SELECT * FROM billings 
                WHERE id=idfactura;
            END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('get_bf_stored_procedures');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('client_id')->unsigned();
            $table->string('note');
            $table->timestamps();
            $table->string('created_by');

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
            ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_notes', function (Blueprint $table) {
            $table->dropForeign(['client_id']); // Drops index 'geo_state_index'
        });

        Schema::dropIfExists('client_notes');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientHasLockersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_has_lockers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('locker_id')->unsigned();
            $table->string('setup_token')->nullable();
            $table->string('key_hash')->nullable();
            $table->timestamp('taken_at')->useCurrent();
            $table->timestamp('invalid_at')->nullable();
            $table->timestamps();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
            ;

            $table->foreign('locker_id')
                ->references('id')
                ->on('lockers')
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
        Schema::dropIfExists('client_has_lockers');
    }
}

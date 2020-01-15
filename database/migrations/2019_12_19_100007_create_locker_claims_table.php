<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLockerClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locker_claims', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('locker_id')->unsigned();
            $table->string('setup_token')->nullable();
            $table->string('key_hash')->nullable();
            $table->tinyInteger('failed_attempts')->unsigned()->default(0);
            $table->timestamp('start_at')->useCurrent();
            $table->timestamp('end_at')->nullable();
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
        Schema::dropIfExists('locker_claims');
    }
}

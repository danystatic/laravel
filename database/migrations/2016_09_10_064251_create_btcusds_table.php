<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBtcusdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('btcusds', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('pesosprice')->default(0);
            $table->string('usdprice')->default(0);
            $table->string('btcprice')->default(0);

            $table->decimal('usd_mxn_buy',7,2)->default(0);
            $table->decimal('usd_mxn_sell',7,2)->default(0);
            $table->decimal('btc_mxn_sell',16,8)->default(0);
            $table->decimal('btc_mxn_buy',16,8)->default(0);
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
        Schema::drop('btcusds');
    }
}

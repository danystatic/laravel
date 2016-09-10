<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockchainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('blockchains', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('blockchain_id')->nullable();
            $table->integer('sender_id')->nullable();
            $table->integer('receiver_id')->nullable();
            $table->integer('commission_id')->nullable();
            $table->integer('cashout_id')->nullable();
            $table->integer('ticket_id')->nullable();
            $table->integer('playnumber')->nullable();
            $table->string('blockchainaddress')->nullable();
            $table->string('input_address')->nullable();
            $table->string('value')->default(0);
            $table->decimal('credit',16,8)->default(0);
            $table->decimal('debit',16,8)->default(0);
            $table->decimal('btcvalue',16,8)->default(0);
            $table->decimal('fee',16,8)->nullable();
            // $table->decimal('amount',16,8)->nullable();
            $table->string('confirmations')->nullable();
            $table->string('custom')->nullable();
            $table->string('type',10)->nullable();
            $table->string('transaction_hash')->nullable();
            $table->string('input_transaction_hash')->nullable();
            $table->string('destination_address')->nullable();
            $table->string('commission')->nullable();
            $table->integer('level')->nullable();           
            $table->string('secret')->nullable();
            $table->string('payer_username')->nullable();
            $table->boolean('callback')->default(0);
            $table->boolean('test')->default(0);
            $table->boolean('buyticket')->default(0);
            $table->string('reason')->default('default');
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
        //
        Schema::drop('blockchains');
    }
}

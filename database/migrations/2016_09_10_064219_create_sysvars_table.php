<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysvarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sysvars', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('playnumber')->nullable();          
            $table->boolean('disabled')->default(0);
            $table->integer('luckynumber')->nullable();         
            $table->decimal('minimumpayment',16,8)->default(0);
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
        Schema::drop('sysvars');
    }

}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('avatar')->nullable();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('sponsor')->nullable();
            $table->string('parent')->nullable();
            $table->string('btcaddress')->nullable();
            $table->string('cellphone')->nullable();
            $table->integer('realsponsorid')->nullable();
            $table->integer('sponsorid')->nullable();
            $table->integer('parentid')->nullable();
            $table->integer('lft')->nullable();
            $table->integer('rgt')->nullable();
            $table->string('ancestors')->nullable();
            $table->string('passwordstring')->nullable();
            $table->string('btc')->nullable();
            $table->string('msg')->nullable();
            $table->integer('limitchildren')->default(3);
            $table->integer('timeoff')->nullable();
            $table->boolean('deleted')->default(0);
            $table->integer('luckynumber')->default(0);
            $table->integer('integer')->nullable();
            $table->boolean('representante')->default(0);
            $table->boolean('confirmed')->default(0);
            $table->string('confirmation_code')->nullable();
            $table->decimal('minimumpayment',16,8)->default(0.0019);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('profiles', function($table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('avatar_original');
            $table->string('gender',6);
            $table->biginteger('uid')->unsigned();
            $table->string('access_token');
            $table->string('access_token_secret');
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
        Schema::drop('users');
        Schema::drop('profiles');
    }
}

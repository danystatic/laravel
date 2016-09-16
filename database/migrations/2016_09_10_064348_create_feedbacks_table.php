<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('feedbacks', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('uuid')->nullable();
            $table->text('json')->nullable();
            $table->string('mykey')->nullable();
            $table->text('value')->nullable();
            $table->decimal('payment',16,8)->nullable();
            $table->integer('playnumber')->nullable();
            $table->integer('paid')->default(0);
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
        Schema::drop('feedbacks');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id', 255);
            $table->mediumText('priv_key');
            $table->mediumText('pub_key');
            $table->string('aes_key', 40);
        });

	    Schema::create('commands', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('client_id', 255);
		    $table->mediumText('command');
		    $table->string('module', 255);
		    $table->dateTime('created_at');
		    $table->dateTime('sent_at');
	    });

	    Schema::create('responses', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('client_id', 255);
		    $table->string('module', 255);
		    $table->integer('command_id');
		    $table->longText('response');
		    $table->dateTime('created_at');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
	    Schema::dropIfExists('commands');
	    Schema::dropIfExists('responses');
    }
}

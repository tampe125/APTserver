<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id', 255);
            $table->mediumText('command');
            $table->string('module', 255);
            $table->char('checksum', 40);
            $table->longText('result');
	        $table->dateTime('created_at');
	        $table->dateTime('executed_at');
	        $table->dateTime('response_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commands');
    }
}

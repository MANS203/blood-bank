<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientPostTable extends Migration {

	public function up()
	{
		Schema::create('client_post', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('client_id')->unsigned();
			$table->integer('post_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('client_post');
	}
}

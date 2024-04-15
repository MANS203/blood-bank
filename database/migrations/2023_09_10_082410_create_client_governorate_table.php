<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientGovernorateTable extends Migration {

	public function up()
	{
		Schema::create('client_governorate', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('client_id')->unsigned();
			$table->integer('governorate_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('client_governorate');
	}
}

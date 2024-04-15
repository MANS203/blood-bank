<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

	public function up()
	{
		Schema::create('posts', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('title');
			$table->string('image');
			$table->text('content');
			$table->integer('category_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('posts');
	}
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

	public function up()
	{
		Schema::create('settings', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->text('notification_settings_text');
			$table->text('about_app');
			$table->string('phone');
			$table->string('email');
			$table->string('fb_link');
			$table->string('tw_link');
			$table->string('insta_link');
			$table->string('youtube_link');
		});
	}

	public function down()
	{
		Schema::drop('settings');
	}
}

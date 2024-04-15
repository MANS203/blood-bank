<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{

    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('phone');
            $table->string('email');
            $table->string('name');
            $table->string('password');
            $table->date('d_o_b');
            $table->date('last_donation_date');
            $table->integer('pin_code')->nullable();
            $table->integer('blood_type_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->string('api_token', 70)->unique()->nullable();
        });
    }

    public function down()
    {
        Schema::drop('clients');
    }
}

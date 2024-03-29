<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the users migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table)
        {
            $table->string('userid')->primary();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->integer('active');/*1 = active, 2 = suspended*/
        });
    }

    /**
     * Reverse the users migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}

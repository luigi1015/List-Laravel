<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserrolesTable extends Migration
{
    /**
     * Run the user roles migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userroles', function (Blueprint $table)
        {
            $table->string('userroleid')->primary();
            $table->string('name');
            $table->timestamps();
			$table->boolean('canEdit')->default(false);
			$table->boolean('canRead')->default(false);
			$table->boolean('canCreate')->default(false);
			$table->boolean('canDelete')->default(false);
        });
    }

    /**
     * Reverse the user roles migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('userroles');
    }
}

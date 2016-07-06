<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersUserrolesForeignKey extends Migration
{
    /**
     * Run the users migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table)
        {
			$table->string('userrole')->nullable();
            $table->foreign('userrole')->references('userroleid')->on('userroles')->onDelete('set null');
        });
    }

    /**
     * Reverse the users migration.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('users', function($table)
		{
			$table->dropForeign('users_role_foreign');
		});
    }
}

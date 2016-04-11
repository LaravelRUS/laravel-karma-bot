<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $t) {
            $t->uuid('id')->unique()->primary();
            $t->string('gitter_id')->index();
            $t->string('name');
            $t->string('login');
            $t->string('avatar');
            $t->string('email')->nullable()->unique();
            $t->string('password', 60)->nullable();
            $t->rememberToken();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}

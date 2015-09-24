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
            $t->increments('id');
            $t->string('gitter_id')->index();
            $t->string('name');
            $t->string('avatar');
            $t->string('url');
            $t->string('login');
            $t->string('email')->nullable()->unique();
            $t->string('password', 60)->nullable();
            $t->integer('karama')->default(0)->index();
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

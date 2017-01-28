<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateUsersTable
 */
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
            $t->string('login');
            $t->string('name');
            $t->string('avatar')->nullable();
            $t->string('url')->nullable();
            $t->string('email')->nullable()->unique();
            $t->string('password', 60)->nullable();
            $t->rememberToken();
            $t->timestamps();
        });

        Schema::create('user_system', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('system_id')->index();
            $t->unsignedInteger('user_id')->index();
            $t->string('sys_user_id');
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
        Schema::drop('user_system');
    }
}

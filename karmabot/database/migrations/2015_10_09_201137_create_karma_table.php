<?php
declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateKarmaTable
 */
class CreateKarmaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karma', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('system_id')->index();
            $t->unsignedInteger('channel_id')->index();
            $t->integer('from_user_id')->index();
            $t->integer('to_user_id')->index();
            $t->string('sys_message_id');
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
        Schema::drop('karma');
    }
}

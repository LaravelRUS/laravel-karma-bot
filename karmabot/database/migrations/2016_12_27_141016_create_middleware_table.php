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
 * Class CreateMiddlewareTable
 */
class CreateMiddlewareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('middleware', function (Blueprint $t) {
            $t->increments('id');
            $t->string('middleware');
            $t->json('options')->nullable();
            $t->unsignedInteger('channel_id')->index();
            $t->unsignedInteger('priority')->default(0)->index();
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
        Schema::drop('middleware');
    }
}

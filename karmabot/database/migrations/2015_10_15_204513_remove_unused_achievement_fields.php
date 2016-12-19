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
 * Class RemoveUnusedAchievementFields
 */
class RemoveUnusedAchievementFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('achievements', function(Blueprint $t) {
            $t->dropColumn([
                'image',
                'title',
                'description'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('achievements', function(Blueprint $t) {
            $t->string('title');
            $t->string('description');
            $t->string('image');
        });
    }
}

<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use KarmaBot\Model\Achieve;

/**
 * Class CreateAchievementsTable
 */
class CreateAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name')->index();
            $t->string('title');
            $t->string('description');
            $t->string('image');
        });

        Schema::create('user_achievements', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('user_id')->index();
            $t->unsignedInteger('achieve_id')->index();
            $t->timestamps();
        });

        $this->fillTable();
    }

    /**
     * Fill table
     *
     * @return void
     */
    private function fillTable(): void
    {
        $achievements = json_decode(file_get_contents(str_replace('.php', '.json', __FILE__)), true);

        foreach ($achievements as $achieve) {
            $model = Achieve::create($achieve);
            echo ' + Achieve ' . $model->title . ' as ' . $model->name . "\n";
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('achievements');
        Schema::drop('user_achievements');
    }
}

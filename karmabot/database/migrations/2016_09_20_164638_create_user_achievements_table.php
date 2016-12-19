<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class CreateUserAchievementsTable
 */
class CreateUserAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws \Throwable
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::rename('achievements', 'user_achievements');

            Schema::table('user_achievements', function (Blueprint $t) {
                $t->integer('achieve_id')->index();
            });

            Schema::create('achievements', function (Blueprint $t) {
                $t->increments('id');
                $t->string('title');
                $t->text('description');
                $t->string('image');
                $t->timestamps();
            });

            $data = $this->initialData();

            DB::table('achievements')->truncate();

            foreach ($data as $id => $item) {
                DB::table('achievements')
                    ->insert(Arr::except($item, 'name'));

                DB::table('user_achievements')
                    ->where('name', $item['name'])
                    ->orWhere('name', // Achieve production bugfix for id #298 and #299
                        str_replace('App\Subscribers', 'Domains\Bot\Achievements', $item['name']))
                    ->update(['achieve_id' => $id + 1])
                ;
            }

            Schema::table('user_achievements', function (Blueprint $t) {
                $t->dropColumn('name');
                $t->unique(['achieve_id', 'user_id'], 'user_achievements_unique');
            });
        });
    }

    /**
     * @return Collection
     */
    private function initialData()
    {
        return new Collection([
            [
                'name'        => 'Domains\\Bot\\Achievements\\DocsAchieve',
                'title'       => 'Красавчик',
                'description' => 'Переводил документацию. Настоящий мужик!',
                'image'       => '//karma.laravel.su/img/achievements/docs.gif',
            ],
            [
                'name'        => 'Domains\\Bot\\Achievements\\GrumblerAchieve',
                'title'       => 'Почётный ворчун',
                'description' => 'Заворчит вас до смерти.',
                'image'       => '//karma.laravel.su/img/achievements/grumbler.gif',
            ],
            [
                'name'        => 'Domains\\Bot\\Achievements\\Karma10Achieve',
                'title'       => 'Находчивый',
                'description' => 'Набрать 10 кармы.',
                'image'       => '//karma.laravel.su/img/achievements/karma-10.gif',
            ],
            [
                'name'        => 'Domains\\Bot\\Achievements\\Karma50Achieve',
                'title'       => 'Любитель сладкого',
                'description' => 'Набрать 50 кармы.',
                'image'       => '//karma.laravel.su/img/achievements/karma-50.gif',
            ],
            [
                'name'        => 'Domains\\Bot\\Achievements\\Karma100Achieve',
                'title'       => 'Благодетель',
                'description' => 'Набрать 100 кармы.',
                'image'       => '//karma.laravel.su/img/achievements/karma-100.gif',
            ],
            [
                'name'        => 'Domains\\Bot\\Achievements\\Karma500Achieve',
                'title'       => 'Рэмбо',
                'description' => 'Набрать 500 кармы.',
                'image'       => '//karma.laravel.su/img/achievements/karma-500.gif',
            ],
            [
                'name'        => 'Domains\\Bot\\Achievements\\Karma1000Achieve',
                'title'       => 'JhaoDa',
                'description' => 'Больше Кармы Богу Кармы!',
                'image'       => '//karma.laravel.su/img/achievements/karma-1000.gif',
            ],
            [
                'name'        => 'Domains\\Bot\\Achievements\\Thanks10Karma0Achieve',
                'title'       => 'Полный паразец!',
                'description' => 'Сказать 10 раз "спасибо" не имея ни единой благодарности.',
                'image'       => '//karma.laravel.su/img/achievements/thanks-10-karma-0.gif',
            ],
            [
                'name'        => 'Domains\\Bot\\Achievements\\Thanks20Achieve',
                'title'       => 'Благодарный',
                'description' => 'Высказать 20 благодарностей.',
                'image'       => '//karma.laravel.su/img/achievements/thanks-20.gif',
            ],
            [
                'name'        => 'Domains\\Bot\\Achievements\\Thanks50Achieve',
                'title'       => 'Нахлебник',
                'description' => 'Сказать 50 раз "спасибо".',
                'image'       => '//karma.laravel.su/img/achievements/thanks-50.gif',
            ],
            [
                'name'        => 'Domains\\Bot\\Achievements\\Thanks100Achieve',
                'title'       => 'Вопрошайка',
                'description' => 'Получить 100 раз ответ на свои вопросы.',
                'image'       => '//karma.laravel.su/img/achievements/thanks-100.gif',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws \Throwable
     */
    public function down()
    {
        Schema::drop('achievements');

        Schema::rename('user_achievements', 'achievements');

        Schema::table('achievements', function (Blueprint $t) {
            $t->dropColumn('achieve_id');
            $t->dropIndex('user_achievements_unique');
        });
    }
}

<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.10.2015 6:07
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Bot\Achievements;

use Domains\Karma;
use Interfaces\Gitter\Achieve\AbstractAchieve;

/**
 * Class Thanks10Karma0Achieve
 */
class Thanks10Karma0Achieve extends AbstractAchieve
{
    /**
     * @var string
     */
    public $title = 'Полный паразец!';

    /**
     * @var string
     */
    public $description = 'Сказать 10 раз "спасибо" не имея ни единой благодарности.';

    /**
     * @var string
     */
    public $image = '//karma.laravel.su/img/achievements/thanks-10-karma-0.gif';

    /**
     * @throws \LogicException
     */
    public function handle()
    {
        Karma::created(function (Karma $karma) {
            $userThanks = $karma->user->thanks->count();
            $userKarma = $karma->user->karma->count();

            if ($userThanks === 10 && $userKarma === 0) {
                $this->create($karma->user, $karma->created_at);
            }
        });
    }
}

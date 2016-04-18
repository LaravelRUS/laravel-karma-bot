<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 15.04.2016 15:35
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Bot\Middlewares\Karma;

use Domains\Bot\Middlewares\Middleware;
use Domains\Message\Message;

/**
 * Class KarmaRenderMiddleware
 * @package Domains\Bot\Middlewares
 */
class KarmaRenderMiddleware implements Middleware
{
    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        if ($message->text->like('карма')) {
            $args = [
                'user'   => $message->user->credinals->login,
                'karma'  => $message->user->karma->count(),
                'thanks' => $message->user->thanks->count(),
            ];

            $karmaMessage = [];

            // Karma info
            $karmaMessage[] = $args['karma']
                ? trans('karma.count.message', $args)
                : trans('karma.count.empty', $args);

            // If has achievements
            //$achievements = $this->getAchievements($message);
            //if ($achievements) {
            //    $karmaMessage[] = $achievements;
            //}

            // Profile link
            $karmaMessage[] = trans('karma.account', $args);

            return implode("\n", $karmaMessage);
        }
    }

    /**
     * @param Message $message
     * @return null|string
     */
    protected function getAchievements(Message $message)
    {
        $achievements = [];
        foreach ($message->user->achievements as $achieve) {
            $achievements[] = '"' . $achieve->title . '"';
        }

        if (count($achievements)) {
            return \Lang::get('karma.achievements', [
                'achievements' => implode(', ', $achievements),
            ]);
        }

        return null;
    }
}

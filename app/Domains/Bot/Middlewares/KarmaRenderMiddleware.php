<?php
namespace Domains\Bot\Middlewares;

use Domains\Message;
use Interfaces\Gitter\Middleware\MiddlewareInterface;

/**
 * Проверяет слово "карма" и выводит статус
 *
 * Class KarmaRenderMiddleware
 */
class KarmaRenderMiddleware implements MiddlewareInterface
{
    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        if (trim(mb_strtolower($message->text)) === 'карма') {
            $args = [
                'user'   => $message->user->login,
                'karma'  => $message->user->karma_text,
                'thanks' => $message->user->thanks_text,
            ];


            $karmaMessage = [];

            // Karma info
            $karmaMessage[] = $args['karma']
                ? \Lang::get('karma.count.message', $args)
                : \Lang::get('karma.count.empty', $args);

            // If has achievements
            $achievements = $this->getAchievements($message);
            if ($achievements) {
                $karmaMessage[] = $achievements;
            }

            // Profile link
            $karmaMessage[] = \Lang::get('karma.account', $args);

            $message->italic(implode("\n", $karmaMessage));

            return null;
        }

        return $message;
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

<?php
namespace Domains\Bot\Middlewares;

use Domains\Message;
use Domains\Middleware\MiddlewareInterface;

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
        if (in_array(trim(mb_strtolower($message->text)), trans('request.karma'), true)) {
            $args = [
                'user' => $message->user->login,
                'karma' => $message->user->karma_text,
                'thanks' => $message->user->thanks_text,
            ];

            $karmaMessage = [];

            // Karma info
            $karmaMessage[] = $args['karma']
                ? trans('karma.count.message', $args)
                : trans('karma.count.empty', $args);

            // If has achievements
            $achievements = $this->getAchievements($message);
            if ($achievements) {
                $karmaMessage[] = $achievements;
            }

            $groups = $message->getRoom()->groups();

            if (in_array('karma', $groups)) {
                $karmaMessage[] = '[list]';
                foreach ($groups as $group) {
                    if (trans()->has($key = "karma.account.{$group}")) {
                        $karmaMessage[] = trans($key, $args);
                    }
                }
                $karmaMessage[] = '[/list]';
            }

            $message->answer(implode("\n", $karmaMessage));

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
            return trans('karma.achievements', [
                'achievements' => implode(', ', $achievements),
            ]);
        }

        return null;
    }
}

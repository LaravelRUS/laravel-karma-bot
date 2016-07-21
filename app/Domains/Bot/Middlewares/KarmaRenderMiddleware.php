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

            if (in_array($message->room_id, ['55dc21c10fc9f982beae822c', '555086c915522ed4b3e03631'], true)) {
                // Profile link
                $karmaMessage[] = trans('karma.account_yii', $args);
            } else {
                $karmaMessage[] = trans('karma.account', $args);
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

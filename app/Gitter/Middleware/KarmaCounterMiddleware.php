<?php
namespace App\Gitter\Middleware;

use App\Gitter\Models\Achieve;
use Lang;
use App\User;
use Carbon\Carbon;
use App\Gitter\Client;
use App\Gitter\Models\MessageObject;

/**
 * @TODO Refactor me
 *
 * Class KarmaCounterMiddleware
 * @package App\Gitter\Middleware
 */
class KarmaCounterMiddleware implements MiddlewareInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * KarmaCounterMiddleware constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param MessageObject $message
     * @return mixed
     */
    public function handle($message)
    {
        $likes = [
            'спасибо',
            'спс',
            'спасибки',
            'спасибище',
            'благодарю',
            'thanks',
            'thx',
            'благодарствую',
            'храни тебя господь',
            'вот благодарочка',
            'благодарочка',
            'спасибо большое',
            'большое спасибо',
        ];

        $this->validate($message, $likes, function (User $user) {
            return $this->addKarma($user);
        });

//
// Temporary remove
//
//        $dislikes = [
//            'иди нафиг',
//            'достал',
//            'убейся',
//            'успокойся',
//            'выпей йаду',
//            'узбагойзя'
//        ];
//
//        $this->validate($message, $dislikes, function(User $user) {
//            return $this->removeKarma($user);
//        });


        // Achieve test @TODO
        foreach ($message->mentions as $user) {
            if ($user->karma == 10) {
                $user->achieve('Десяточка', 'Получить 10 кармы', 'http://docs.rudev.org/stream/a265faa4be6dbd24f957db97b89c4e51');
            }
        }

        return $message;
    }

    /**
     * @param $message
     * @param array $words
     * @param callable $callback
     */
    protected function validate($message, array $words = [], callable $callback)
    {
        $dataPattern = '/@([0-9a-zA-Z_]+)\s+%s\b/iu';

        foreach ($words as $word) {
            $pattern = sprintf($dataPattern, preg_quote($word));

            if (preg_match($pattern, $message->text)) {

                $response = [];

                foreach ($message->mentions as $user) {

                    // If self message
                    if ($message->user->gitter_id === $user->gitter_id) {
                        $response[] = $this->selfKarma($user);
                        continue;
                    }

                    // Check karma timeout
                    $timeout = $user->updated_at->timestamp + 60 > Carbon::now()->timestamp;
                    $response[] = $timeout
                        ? $this->timeoutKarma($user)
                        : $callback($user);
                }

                // Has one or more valid mentions
                if (count($response)) {
                    $message->italic(implode("\n", $response));
                }
            }
        }
    }

    /**
     * @param User $user
     * @return string
     */
    protected function selfKarma(User $user)
    {
        return Lang::get('karma.self', [
            'user' => $user->login
        ]);
    }

    /**
     * @param User $user
     * @return string
     */
    protected function timeoutKarma(User $user)
    {
        return Lang::get('karma.timeout', [
            'user' => $user->login,
        ]);
    }

    /**
     * @param User $user
     * @return string
     */
    protected function removeKarma(User $user)
    {
        $user->decrement('karma');

        return Lang::get('karma.decrement', [
            'user'  => $user->login,
            'karma' => $user->karma_text,
        ]);
    }

    /**
     * @param User $user
     * @return string
     */
    protected function addKarma(User $user)
    {
        $user->increment('karma');

        return Lang::get('karma.increment', [
            'user'  => $user->login,
            'karma' => $user->karma_text,
        ]);
    }
}
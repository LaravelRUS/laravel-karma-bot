<?php
namespace App\Gitter\Middleware;
use App\Gitter\Client;
use App\Gitter\Models\MessageObject;
use App\User;

/**
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
        $dataPattern = '/@([0-9a-zA-Z_]+)\s+%s\b/iu';

        $words = [
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
            'большое спасибо'
        ];

        foreach ($words as $word) {
            $pattern = sprintf($dataPattern, preg_quote($word));

            if (preg_match($pattern, $message->text)) {

                $response = [];
                foreach ($message->mentions as $user) {
                    $response[] = $this->addKarma($user);
                }

                $message->italic(implode("\n", $response));
            }
        }

        return $message;
    }

    protected function addKarma(User $user)
    {
        $user->increment('karma');
        return 'Спасибо для @' . $user->login . ' (+' . $user->karma . ') принято!';
    }
}
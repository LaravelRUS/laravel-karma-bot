<?php
namespace App\Middlewares;

use App\Gitter\Ai;
use App\Message;
use App\Gitter\Karma\Validator;
use App\Gitter\Middleware\MiddlewareInterface;
use App\User;

/**
 * Class PersonalAnswersMiddleware
 * @package App\Gitter\Middleware
 */
class PersonalAnswersMiddleware implements MiddlewareInterface
{
    /**
     * @var Ai
     */
    protected $ai;

    /**
     * PersonalAnswersMiddleware constructor.
     */
    public function __construct()
    {
        $this->ai = new Ai();
    }

    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        // Personal message
        $isBotMention = $message->hasMention(function(User $user) {
            return $user->login === \Auth::user()->login;
        });

        if ($isBotMention) {
            $this->ai->handle($message);
        }



        // Hello all
        $isHello = in_array($message->text_without_special_chars, [
            'привет всем',
            'всем привет',
            'здравствуйте'
        ], false);

        if ($isHello) {
            $id = array_rand(\Lang::get('personal.hello'));

            $message->italic(\Lang::get('personal.hello.' . $id, [
                'user' => $message->user->login
            ]));
        }

        return $message;
    }
}

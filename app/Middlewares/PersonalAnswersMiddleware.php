<?php
namespace App\Middlewares;

use App\Gitter\Ai;
use Domains\Message;
use App\Gitter\Karma\Validator;
use App\Gitter\Middleware\MiddlewareInterface;
use Domains\User;
use Illuminate\Support\Str;

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
        if ($message->user->login === \Auth::user()->login) {
            return $message;
        }

        // Personal message
        $isBotMention = $message->hasMention(function(User $user) {
            return $user->login === \Auth::user()->login;
        });

        if ($isBotMention) {
            //$this->ai->handle($message);


        } else {

            // Hello all
            $isHello = Str::contains($message->text_without_special_chars, \Lang::get('personal.hello_query'));

            if ($isHello) {
                $id = array_rand(\Lang::get('personal.hello'));

                $message->italic(\Lang::get('personal.hello.' . $id, [
                    'user' => $message->user->login
                ]));
            }


            // Question
            $isQuestion = Str::contains($message->text_without_special_chars, [
                'можно задать вопрос',
                'хочу задать вопрос'
            ]);

            if ($isQuestion) {
                $message->italic(sprintf('@%s, и какой ответ ты ожидаешь услышать?', $message->user->login));
            }

        }

        return $message;
    }
}

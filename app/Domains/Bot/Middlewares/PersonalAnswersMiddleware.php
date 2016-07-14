<?php
namespace Domains\Bot\Middlewares;

use Interfaces\Gitter\Ai;
use Domains\Message;
use Interfaces\Gitter\Karma\Validator;
use Interfaces\Gitter\Middleware\MiddlewareInterface;
use Domains\User;
use Illuminate\Support\Str;

/**
 * Class PersonalAnswersMiddleware
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

        $noMentions = !count($message->mentions);

        // Personal message
        $isBotMention = $message->hasMention(function(User $user) {
            return $user->login === \Auth::user()->login;
        });

        if ($isBotMention || $noMentions) {
            // Hello all
            $isHello = Str::contains($message->text_without_special_chars, \Lang::get('personal.hello_query'));

            if ($isHello) {
                $id = array_rand(\Lang::get('personal.hello'));

                $message->italic(\Lang::get('personal.hello.' . $id, [
                    'user' => $message->user->login
                ]));
            }
        }

        if (!count($message->mentions)) {
            // Question
            $isQuestion = in_array($message->text_without_special_chars, [
                'можно задать вопрос',
                'хочу задать вопрос',
                'есть кто',
                'кто может помочь',
                'помогите пожалуйста'
            ], true);

            if ($isQuestion) {
                $message->italic(sprintf('@%s, и какой ответ ты ожидаешь услышать?', $message->user->login));
            }

            // Question
            $isCats = Str::contains($message->text_without_special_chars, ['котаны']);

            if ($isCats) {
                $message->italic(sprintf('@%s, в Пензу езжай со своими котанами \-_\-', $message->user->login));
            }

            // Question
            $isPolitics = Str::contains($message->text_without_special_chars, [
                'яровая',
                'пакет яровой',
                'пакетом яровой',
                'пакете яровой',
                'пакету яровой',
                'мизулина'
            ]);

            if ($isPolitics) {
                $message->italic(sprintf('@%s, :see_no_evil: :fire: ', $message->user->login));
            }

            $isRules = in_array($message->text_without_special_chars, [
                'правила чата',
                'правила',
                'как себя вести',
                '9 кругов'
            ], true);

            if ($isRules) {
                $message->italic(sprintf('@%s, [In rules we trust](http://laravel.su/articles/nine-circles-of-chat)', $message->user->login));
            }

            $isBan = in_array($message->text_without_special_chars, [
                'банхаммер',
                'хаммер',
                'бан',
            ], true);

            if ($isBan) {
                $message->italic(sprintf(
                    '@%s, тебе выданы ' . str_repeat(' :hammer: ', random_int(1, 9)) . ' на 0.' . random_int(1, 9) . ' секунды. Наслаждайся ;)',
                        $message->user->login
                ));
            }

            $isPolitics = in_array($message->text_without_special_chars, [
                'майдан',
                'революция',
                'битрикс',
                'yii',
                'wordpress',
                'вордпресс',
                'laravel',
                'ларавель',
                'йии',
            ], true);

            if ($isPolitics) {
                $message->italic(sprintf(
                    '@%s, за ' . $message->text_without_special_chars . '! ' . str_repeat(' :monkey: ', random_int(1, 9)),
                    $message->user->login
                ));
            }

            if (preg_match('/^[a-zA-Z][0-9]$/isu', $message->text_without_special_chars)) {
                $message->italic(sprintf('@%s, %s', $message->user->login, ['мимо', 'ранил', 'убил'][random_int(0, 2)]));
            }
        }

        return $message;
    }
}

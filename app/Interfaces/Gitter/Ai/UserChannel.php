<?php
namespace Interfaces\Gitter\Ai;

use Domains\Message;
use Domains\User;
use Illuminate\Support\Str;

/**
 * Class UserChannel
 */
class UserChannel
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $lastAnswer = '';

    /**
     * UserChannel constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        if ($this->has($message, ['привет', 'ку', 'здравствуй'])) {
            $this->answer($message, 'ну привет, коль не шутишь :)');


        } else if ($this->has($message, 'как дела')) {
            $this->answer($message, 'пока не родила :trollface:');


        } else if ($this->has($message, 'когда родишь')) {
            if ($this->lastAnswer === 'пока не родила :trollface:') {
                $this->answer($message, 'в процессе :D');
            } else {
                $this->answer($message, 'м?');
            }

        } else {
            $this->answer($message, $message->text_without_special_chars . '?');
        }
    }

    /**
     * @param Message $message
     * @param $text
     * @return bool
     */
    protected function has(Message $message, $text)
    {
        return Str::contains($message->text_without_special_chars, $text);
    }

    /**
     * @param Message $message
     * @param $text
     */
    protected function answer(Message $message, $text)
    {
        $this->lastAnswer = $text;
        $message->italic(sprintf('@%s, ' . $text, $this->user->login));
    }
}
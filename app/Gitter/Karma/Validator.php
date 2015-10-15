<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 09.10.2015 16:35
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Gitter\Karma;

use App\Message;
use Carbon\Carbon;

/**
 * Class Validator
 * @package App\Gitter\Support
 */
class Validator
{
    /**
     * @var array
     */
    protected $likes = [];

    /**
     * @var array
     */
    protected $dislikes = [];

    /**
     * Validator constructor.
     */
    public function __construct()
    {
        $this->likes = [
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

        $this->dislikes = [
            'иди нафиг',
            'достал',
            'убейся',
            'успокойся',
            'выпей йаду',
            'узбагойзя'
        ];



    }


    /**
     * @param Message $message
     * @param bool $ignoreTimeout
     * @return Status
     */
    public function validate(Message $message, $ignoreTimeout = false)
    {
        // Check karma increment
        $status = $this->validateMessage($message, Status::STATUS_INCREMENT, $this->likes, $ignoreTimeout);
        if (!$status->isNothing()) {
            return $status;
        }

        // Check karma decrement
        $status = $this->validateMessage($message, Status::STATUS_DECREMENT, $this->dislikes, $ignoreTimeout);
        if (!$status->isNothing()) {
            return $status;
        }

        return new Status(Status::STATUS_NOTHING);
    }

    /**
     * @param Message $message
     * @param $validStatus
     * @param array $words
     * @param bool $ignoreTimeout
     * @return Status
     */
    protected function validateMessage(Message $message, $validStatus, array $words = [], $ignoreTimeout = false)
    {
        if ($this->validateText($message, $words)) {
            if (!$this->validateUser($message)) {
                return new Status(Status::STATUS_SELF);
            }

            if (!$ignoreTimeout && !$this->validateTimeout($message)) {
                return new Status(Status::STATUS_TIMEOUT);
            }

            return new Status($validStatus);
        }

        return new Status(Status::STATUS_NOTHING);
    }

    /**
     * @param Message $message
     * @return bool
     */
    protected function validateUser(Message $message)
    {
        foreach ($message->mentions as $user) {
            if ($message->user->login === $user->login) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Message $message
     * @return bool
     */
    protected function validateTimeout(Message $message)
    {
        foreach ($message->mentions as $user) {
            if ((new Carbon($user->last_karma_time))->timestamp + 60 > Carbon::now()->timestamp) {
                return false;
            }
        }
        return true;
    }


    /**
     * @TODO Анализировать каждое предложение (разбить по точке и новой строке)
     *
     * @param $message
     * @param array $words
     * @return bool
     */
    protected function validateText(Message $message, array $words = [])
    {
        if (count($words)) {
            if ($message->user->login === \Auth::user()->login) {
                return false;
            }


            // Если "@Some спасибо"
            $escaped = implode('|', array_map(function ($word) { return preg_quote($word); }, $words));
            $pattern = sprintf('/@([0-9a-zA-Z_]+)\s+(?:%s)\b/iu', $escaped);

            if (preg_match($pattern, $message->text)) {
                return true;
            }

            // Если "спасибо" в начале или конце предложения
            $escapedText = $message->text;
            $escapedText = mb_strtolower($escapedText);
            $escapedText = preg_replace('/@([0-9a-zA-Z\- \/_?:.,\s]+)\s+/isu', '', $escapedText);
            $escapedText = preg_replace('/[.,-\/#!$%\^&\*;:{}=\-_`~()]/su', '', $escapedText);
            $escapedText = trim($escapedText);

            $atStart     = preg_match(sprintf('/^(?:%s)/isu', $escaped), $escapedText);
            $atEnd       = preg_match(sprintf('/(?:%s)$/isu', $escaped), $escapedText);

            if ($atStart || $atEnd) {
                return true;
            }
        }

        return false;
    }
}

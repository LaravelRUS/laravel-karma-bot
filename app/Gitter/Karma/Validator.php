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
     * @return Status
     */
    public function validate(Message $message)
    {
        // Check karma increment
        $status = $this->validateMessage($message, Status::STATUS_INCREMENT, $this->likes);
        if (!$status->isNothing()) {
            return $status;
        }

        // Check karma decrement
        $status = $this->validateMessage($message, Status::STATUS_DECREMENT, $this->dislikes);
        if (!$status->isNothing()) {
            return $status;
        }

        return new Status(Status::STATUS_NOTHING);
    }

    /**
     * @param Message $message
     * @param $validStatus
     * @param array $words
     * @return Status
     */
    protected function validateMessage(Message $message, $validStatus, array $words = [])
    {
        if ($this->validateText($message, $words)) {
            if (!$this->validateUser($message)) {
                return new Status(Status::STATUS_SELF);
            }

            if (!$this->validateTimeout($message)) {
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
        return \Auth::user()->login !== $message->user;
    }

    /**
     * @param Message $message
     * @return bool
     */
    protected function validateTimeout(Message $message)
    {
        return $message->updated_at->timestamp + 60 <= Carbon::now()->timestamp;
    }


    /**
     * @TODO Анализировать "спасибки" и вконце предложения тоже
     * @TODO Анализировать каждое предложение (разбить по точке и новой строке)
     *
     * @param $message
     * @param array $words
     * @return bool
     */
    protected function validateText(Message $message, array $words = [])
    {
        if (count($words)) {

            // Если "@Some спасибо"
            $escaped = implode('|', array_map(function ($word) { return preg_quote($word); }, $words));
            $pattern = sprintf('/@([0-9a-zA-Z_]+)\s+%s\b/iu', $escaped);

            if (preg_match($pattern, $message->text)) {
                return true;
            }

            // Если "спасибо" в начале или конце предложения
            $escapedText = $message->text;
            $escapedText = mb_strtolower($escapedText);
            $escapedText = preg_replace('/@([0-9a-zA-Z\- \/_?:.,\s]+) /isu', '', $escapedText);
            $escapedText = preg_replace('/[.,-\/#!$%\^&\*;:{}=\-_`~()]/su', '', $escapedText);

            $atStart     = preg_match(sprintf('/^%s/isu', $escaped), $escapedText);
            $atEnd       = preg_match(sprintf('/%s$/isu', $escaped), $escapedText);

            if ($atStart || $atEnd) {
                return true;
            }
        }

        return false;
    }
}

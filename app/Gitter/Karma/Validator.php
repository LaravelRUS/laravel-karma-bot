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
     * @var string
     */
    protected $pattern = '/@([0-9a-zA-Z_]+)\s+%s\b/iu';

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
        if ($this->validateText($message->text, $words)) {
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
     * @param $text
     * @param array $words
     * @return bool
     */
    protected function validateText($text, array $words = [])
    {
        if (count($words)) {
            $escaped = array_map(function ($word) {
                return preg_quote($word);
            }, $words);

            $pattern = sprintf($this->pattern, implode('|', $escaped));

            if (preg_match($pattern, $text)) {
                return true;
            }
        }

        return false;
    }
}

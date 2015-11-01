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

use App\User;
use App\Message;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
     * Validator constructor.
     */
    public function __construct()
    {
        $this->likes = \Lang::get('thanks.likes');
    }

    /**
     * @param Message $message
     * @return Status[]|Collection
     */
    public function validate(Message $message)
    {
        $response = new Collection([]);

        // If has no mentions
        if (!count($message->mentions)) {
            if ($this->validateText($message)) {
                $response->push(new Status($message->user, Status::STATUS_NO_USER));
            }

            return $response;
        }

        foreach ($message->mentions as $mention) {
            // Ignore bot queries
            if (\Auth::user()->login === $message->user->login) {
                continue;
            }

            $response->push($this->validateMessage($message, $mention));
        }

        return $response;
    }

    /**
     * @param Message $message
     * @param User $mention
     * @return Status
     */
    protected function validateMessage(Message $message, User $mention)
    {
        if ($this->validateText($message)) {
            if (!$this->validateUser($message, $mention)) {
                return new Status($mention, Status::STATUS_SELF);
            }

            if (!$this->validateTimeout($message, $mention)) {
                return new Status($mention, Status::STATUS_TIMEOUT);
            }

            return new Status($mention, Status::STATUS_INCREMENT);
        }

        return new Status($mention, Status::STATUS_NOTHING);
    }

    /**
     * @param Message $message
     * @param User $mention
     * @return bool
     */
    protected function validateUser(Message $message, User $mention)
    {
        return $mention->login !== $message->user->login;
    }

    /**
     * @param Message $message
     * @param User $mention
     * @return bool
     */
    protected function validateTimeout(Message $message, User $mention)
    {
        return $mention->getLastKarmaTimeForRoom($message->room_id)->timestamp + 60
            < $message->created_at->timestamp;
    }


    /**
     * @param Message $message
     * @return bool
     */
    protected function validateText(Message $message)
    {
        $escapedText = mb_strtolower($message->text);
        $escapedText = preg_replace('/\@[a-z0-9\-_]+/iu', '', $escapedText);
        $escapedText = preg_replace('/\W+/iu', '', $escapedText);
        $escapedText = trim($escapedText);

        return Str::endsWith($escapedText, $this->likes) || Str::startsWith($escapedText, $this->likes);
    }
}

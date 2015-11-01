<?php
namespace App\Gitter;

use App\User;
use App\Message;
use App\Gitter\Ai\UserChannel;

/**
 * Class Ai
 * @package App\Gitter
 */
class Ai
{
    /**
     * @var array|UserChannel[]
     */
    protected $channels = [];

    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        $this
            ->getChannel($message->user)
            ->handle($message);
    }

    /**
     * @param User $user
     * @return UserChannel
     */
    protected function getChannel(User $user)
    {
        $key = $user->gitter_id;
        if (!array_key_exists($key, $this->channels)) {
            $this->channels[$key] = new UserChannel($user);
        }
        return $this->channels[$key];
    }
}
<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 15:47
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter\Middleware;


use App\User;
use App\Gitter\Client;
use App\Gitter\Models\UserObject;
use App\Gitter\Models\MessageObject;


/**
 * Class DbSyncMiddleware
 * @package App\Gitter\Middleware
 */
class DbSyncMiddleware implements MiddlewareInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * DbSyncMiddleware constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function handle($data)
    {
        if ($data instanceof MessageObject) {
            return $this->applyUsers($data);
        }
        return $data;
    }

    /**
     * @param MessageObject $message
     * @return MessageObject
     */
    protected function applyUsers(MessageObject $message)
    {
        $message->user = User::fromGitterObject($message->user);

        $this->fromMentions($message);

        return $message;
    }

    /**
     * @param MessageObject $message
     */
    protected function fromMentions(MessageObject $message)
    {
        $mentions = [];

        foreach ($message->mentions as $mention) {
            if (array_key_exists('userId', $mention)) {
                $user = User::where('gitter_id', $mention['userId'])->first();
                $mentions[] = $user;
            }
        }

        $message->set('mentions', $mentions);
    }
}

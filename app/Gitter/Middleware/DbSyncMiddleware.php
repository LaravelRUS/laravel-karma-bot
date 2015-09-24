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


use App\Gitter\Client;
use App\Gitter\Models\UserObject;
use App\User;
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
        $message->user = $this->fromGitterModel($message->user);

        $this->fromMentions($message);

        return $message;
    }

    /**
     * @TODO Add User::class memory pool (with GC probably)
     *
     * @param UserObject $userObject
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    protected function fromGitterModel(UserObject $userObject)
    {
        $user = User::where('gitter_id', $userObject->gitter_id)->first();
        if (!$user) {
            $user = User::create($userObject->toArray());
        }
        return $user;
    }

    /**
     * @param MessageObject $message
     */
    protected function fromMentions(MessageObject $message)
    {
        foreach ($message->mentions as $mention) {
            if (array_key_exists('userId', $mention)) {

                #$response = $this->client->request('user', ['userId' => $mention['userId']]);
                #var_dump($response); // @TODO fix 403 error

            }
        }
    }
}

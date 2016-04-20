<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.04.2016 17:41
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Gitter\Factories;

use Core\Repositories\Services\GitterServiceRepository;
use Core\Repositories\User\UsersRepository;
use Domains\User\User;

/**
 * Class UserFactory
 * @package Interfaces\Gitter\Factories
 */
class UserFactory extends ServiceFactory
{
    /**
     * @var UsersRepository
     */
    private $users;

    /**
     * UserFactory constructor.
     * @param GitterServiceRepository $services
     * @param UsersRepository $users
     */
    public function __construct(GitterServiceRepository $services, UsersRepository $users)
    {
        parent::__construct($services);

        $this->users = $users;
    }

    /**
     * @param \StdClass $data
     * @param bool $sync
     * @return User
     */
    public function fromUser($data, $sync = false) : User
    {
        $service = $this->fromServiceId($data->id);

        /** @var User $user */
        $user = $this->users->find($service->id);

        if ($user === null) {
            $user = new User(['id' => $service->id]);
            $sync = true;
        }

        if ($sync) {
            $user->setRawAttributes(array_merge($user->getAttributes(), [
                'name'   => $data->displayName,
                'login'  => $data->username,
                'avatar' => $data->avatarUrlMedium,
            ]));

            $user->save();
        }

        return $user;
    }

    /**
     * @param \StdClass $data
     * @param bool $sync
     * @return User
     */
    public function fromMention($data, $sync = false) : User
    {
        $service = $this->fromServiceId($data->userId);

        /** @var User $user */
        $user = $this->users->find($service->id);

        if ($user === null) {
            $user = new User([
                'id'     => $service->id,
                'name'   => $data->screenName,
                'avatar' => sprintf('https://github.com/identicons/%s.png?v=3&s=128', $data->screenName),
            ]);
            $sync = true;
        }

        if ($sync) {
            $user->setRawAttributes(array_merge($user->getAttributes(), [
                'login'  => $data->screenName,
            ]));

            $user->save();
        }

        return $user;
    }

    /**
     * @param $data
     * @return bool
     */
    public function isValidMention($data) : bool
    {
        return property_exists($data, 'userId');
    }
}

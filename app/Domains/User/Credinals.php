<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.04.2016 17:06
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\User;

use Doctrine\ORM\Mapping as ORM;
use Serafim\Properties\Getters;

/**
 * @ORM\Embeddable
 *
 * @property-read string $login
 * @property-read string $password
 * @property-read string|null $email
 */
class Credinals
{
    use Getters;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $login;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * Credinals constructor.
     * @param string $login
     * @param string $password
     * @param string $email
     */
    public function __construct(string $login, string $password, string $email = null)
    {
        $this->login = $login;
        $this->email = $email;
        $this->updatePassword($password);
    }

    /**
     * @param string $password
     * @return $this|Credinals
     * @throws \RuntimeException
     */
    public function updatePassword(string $password) : Credinals
    {
        $this->password = \Hash::make($password);

        return $this;
    }

    /**
     * @param string $email
     * @return Credinals
     */
    public function changeEmail(string $email) : Credinals
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $login
     * @return Credinals
     */
    public function changeLogin(string $login) : Credinals
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return null|string
     */
    protected function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    protected function getLogin() : string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    protected function getPassword() : string
    {
        return $this->password;
    }
}

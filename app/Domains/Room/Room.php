<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.04.2016 16:14
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Room;

use Core\Entity\Getters;
use Ramsey\Uuid\Uuid;

/**
 * Class Room
 * @package Domains\Room
 * @property-read string $url
 * @property-read string $title
 */
class Room
{
    use Getters;

    /**
     * @var string|null
     */
    public $gitterId = null;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    private $id;

    /**
     * Room constructor.
     * @param string $url
     * @param string|null $title
     */
    public function __construct(string $url, string $title = null)
    {
        $this->id  = Uuid::uuid4()->toString();
        $this->url = $url;
        if ($title === null) {
            $this->title = $this->url;
        }
    }

    /**
     * @return string
     */
    protected function getTitle()
    {
        return (string)$this->title;
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return (string)$this->url;
    }
}

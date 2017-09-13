<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.10.2015 6:09
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Gitter\Achieve;

use Domains\User;
use Domains\Achieve;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class AbstractAchieve
 */
abstract class AbstractAchieve implements
    AchieveInterface,
    Arrayable,
    Jsonable
{
    /**
     * Achieve title
     * @var string
     */
    public $title = 'undefined';

    /**
     * Achieve description
     * @var string
     */
    public $description = 'undefined';

    /**
     * Achieve image link
     * @var string
     */
    public $image = '/img/achievements/karma-10.gif';

    /**
     * @var null
     */
    public $name = null;

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @constructor
     */
    public function __construct()
    {
        $this->name = basename(static::class);
    }

    /**
     * @param User $user
     * @param Carbon|null $createdAt
     * @return Achieve
     * @throws \LogicException
     */
    public function create(User $user, Carbon $createdAt = null): Achieve
    {
        $achieve = Achieve::create([
            'name'       => $this->name,
            'user_id'    => $user->id,
            'created_at' => $createdAt ?: Carbon::now(),
        ]);

        return $achieve;
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->properties[$key] = $value;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_merge([
            'name'        => $this->name,
            'title'       => $this->title,
            'description' => $this->description,
            'image'       => $this->image,
        ], $this->properties);
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}

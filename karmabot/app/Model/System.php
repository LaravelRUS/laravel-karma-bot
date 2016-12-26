<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Model;

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use KarmaBot\Model\System\DriversMap;
use Psr\Log\LoggerInterface;
use Serafim\KarmaCore\Factory;
use Serafim\KarmaCore\Io\SystemInterface;

/**
 * Class System
 * @package KarmaBot\Model
 *
 * @property string $driver_class
 */
class System extends Model
{
    /**
     * @var string
     */
    protected $table = 'systems';

    /**
     * @var array
     */
    protected $fillable = ['title', 'name', 'adapter', 'token', 'icon'];

    /**
     * @var array
     */
    protected $appends = ['driver_class'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function channels()
    {
        return $this->hasMany(Channel::class);
    }

    /**
     * @param Container $container
     * @return \Serafim\KarmaCore\Io\SystemInterface
     * @throws \InvalidArgumentException
     */
    public function getSystemConnection(Container $container): SystemInterface
    {
        $factory = $container->make(Factory::class);
        $factory->setLogger($container->make(LoggerInterface::class));

        return $factory->create($this->driver_class, ['token' => $this->token]);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getDriverClassAttribute(): string
    {
        return DriversMap::getDriverByAlias($this->driver);
    }

    /**
     * @param string $class
     */
    public function setDriverClassAttribute(string $class): void
    {
        $this->attributes['driver'] = DriversMap::findAliasByDriver($class);
    }
}

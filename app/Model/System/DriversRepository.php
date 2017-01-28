<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Model\System;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Serafim\KarmaCore\System\Gitter\GitterSystem;
use Serafim\KarmaCore\System\Slack\SlackSystem;

/**
 * Class DriversRepositoryDriversRepository
 * @package KarmaBot\Model\System
 */
final class DriversRepository implements Arrayable
{
    /**
     * @var Collection
     */
    private $items;

    /**
     * DriversMap constructor.
     */
    public function __construct()
    {
        $this->items = new Collection([
            new Driver('gitter', GitterSystem::class),
            new Driver('slack', SlackSystem::class),
        ]);
    }

    /**
     * @return array
     */
    public function drivers(): array
    {
        return $this->items->pluck('driver')->toArray();
    }

    /**
     * @return array
     */
    public function aliases(): array
    {
        return $this->items->pluck('alias')->toArray();
    }

    /**
     * @param string $field
     * @param string $value
     * @return Collection
     */
    public function find(string $field, string $value): Collection
    {
        return $this->items->filter(function (Driver $driver) use ($field, $value) {
            return $driver->{$field} === $value;
        });
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->items->toArray();
    }
}

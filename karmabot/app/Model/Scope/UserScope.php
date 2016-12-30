<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Model\Scope;

use Illuminate\Database\Eloquent\Builder;
use KarmaBot\Model\System;
use KarmaBot\Model\User;
use Serafim\KarmaCore\Io\UserInterface;

/**
 * Class UserScope
 * @package KarmaBot\Model\Scope
 * @mixin User
 */
trait UserScope
{
    /**
     * @var string
     */
    private static $sysPivotTable;

    /**
     * @return string
     */
    public static function systemsPivotTable(): string
    {
        if (self::$sysPivotTable === null) {
            self::$sysPivotTable = (new User())->systems()->getTable();
        }

        return self::$sysPivotTable;
    }

    /**
     * @param Builder $builder
     * @param System $system
     * @param UserInterface $user
     * @return Builder|\Illuminate\Database\Query\Builder|UserScope
     */
    public static function scopeWhereExternalUser(Builder $builder, System $system, UserInterface $user)
    {
        $ids = \DB::table(static::systemsPivotTable())
            ->where('system_id', $system->id)
            ->where('sys_user_id', $user->getId())
            ->get()
            ->pluck('user_id');


        return $builder->whereIn('id', $ids);
    }
}
